job "betting" {
  type = "service"

  group "betting" {
    network {
      port "http" { }
    }

    service {
      name     = "betting"
      port     = "http"
      provider = "nomad"
      tags = [
        "traefik.enable=true",
        "traefik.http.routers.betting.rule=Host(`betting.datasektionen.se`)",
        "traefik.http.routers.betting.tls.certresolver=default",
      ]
    }

    task "betting" {
      driver = "docker"

      config {
        image = var.image_tag
        ports = ["http"]

        volumes = [
          "local/nginx.conf:/etc/nginx/conf.d/default.conf"
        ]
      }

      template {
        data = <<EOF
server {
    listen 80;
    server_name _;
    root /var/www/html/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME /var/www/html/public$fastcgi_script_name;
        include fastcgi_params;
    }
}
EOF
        destination = "local/nginx.conf"
      }

      template {
        data        = <<ENV
{{ with nomadVar "nomad/jobs/betting" }}
APP_KEY={{ .app_secret_key }}
DB_PASSWORD={{ .db_password }}
OIDC_SECRET={{ .oidc_secret }}
RFINGER_API_KEY={{ .rfinger_api_key }}
{{ end }}

PORT={{ env "NOMAD_PORT_http" }}

APP_NAME=skywhale
APP_DEBUG=false
APP_ENV=production

LOG_CHANNEL=stderr
LOG_LEVEL=info

SESSION_DRIVER=cookie
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_PATH=/
SESSION_DOMAIN=null

DB_CONNECTION=pgsql
DB_DRIVER=pgsql
DB_DATABASE=betting
DB_HOST=postgres.dsekt.internal
DB_PORT=5432
DB_USERNAME=betting

SSO_API_URL="http://sso.nomad.dsekt.internal"

OIDC_PROVIDER=http://sso.nomad.dsekt.internal/op
OIDC_ID=betting
REDIRECT_URL=https://betting.datasektionen.se/login-complete

RFINGER_API_URL=https://rfinger.datasektionen.se/api
ENV
        destination = "local/.env"
        env         = true
      }

      resources {
        memory = 120
      }
    }
  }
}

variable "image_tag" {
  type = string
  default = "ghcr.io/datasektionen/betting:latest"
}
