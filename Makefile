# Definisikan perintah sail
SAIL := ./vendor/bin/sail

# Ambil nama database dari .env
DB_NAME := $(shell grep ^DB_DATABASE .env | cut -d '=' -f2)

# 1. Setup Awal (Full Automation)
setup:
	@echo "1. Menginstall Dependencies (Vendor)..."
	docker run --rm \
	    -u "$$(id -u):$$(id -g)" \
	    -v "$$(pwd):/var/www/html" \
	    -w /var/www/html \
	    laravelsail/php83-composer:latest \
	    composer install --ignore-platform-reqs

	@echo "2. Copy file .env (jika belum ada)..."
	cp -n .env.example .env || true

	@echo "3. Menyalakan Container..."
	$(SAIL) up -d

	@echo "4. Generate Key..."
	$(SAIL) artisan key:generate

	@echo "5. Menunggu Database siap (5 detik)..."
	@sleep 5

	@echo "5b. Memastikan Database '$(DB_NAME)' tersedia..."
	$(SAIL) exec mysql mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS \`$(DB_NAME)\`; GRANT ALL PRIVILEGES ON \`$(DB_NAME)\`.* TO 'sail'@'%';"
	@echo "✅ Database '$(DB_NAME)' siap."

	@echo "6. Migrasi Database..."
	$(SAIL) artisan migrate --seed

	@echo "7. Setup Frontend (NPM Install & Build)..."
	$(SAIL) npm install
	$(SAIL) npm run build

	@echo "✅ Setup Selesai! Buka http://localhost"

# 2. Menyalakan Server
up:
	$(SAIL) up -d
	@echo "Server berjalan di http://localhost"

# 3. Mematikan Server
down:
	$(SAIL) stop
	@echo "Server dimatikan."

# 4. Shortcut Frontend Build (Manual)
build:
	$(SAIL) npm install
	$(SAIL) npm run build
	@echo "✅ Frontend assets built."

# 5. Shortcut Frontend Dev (Hot Reload)
# Cara pakai: make dev
dev:
	$(SAIL) npm run dev

# 6. Masuk Terminal
shell:
	$(SAIL) shell

# 7. Lihat Logs
logs:
	$(SAIL) logs -f

# 8. Reset Total
destroy:
	$(SAIL) down -v
	@echo "💥 Container dan Volume Database telah dihapus."

# 9. Database Tools
db-create:
	@echo "Membuat database: $(name)..."
	$(SAIL) exec mysql mysql -u root -ppassword -e "CREATE DATABASE IF NOT EXISTS \`$(name)\`; GRANT ALL PRIVILEGES ON \`$(name)\`.* TO 'sail'@'%';"

db-drop:
	@echo "Menghapus database: $(name)..."
	$(SAIL) exec mysql mysql -u root -ppassword -e "DROP DATABASE IF EXISTS \`$(name)\`;"