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
      }

      template {
        data        = <<ENV
{{ with nomadVar "nomad/jobs/betting" }}
APP_KEY={{ .app_secret_key }}
DATABASE_URL="postgres://betting:{{ .db_password }}@postgres.dsekt.internal:5432/betting"
DB_PASSWORD={{ .db_password }}
LOGIN_API_KEY={{ .login_key }}
{{ end }}
PORT={{ env "NOMAD_PORT_http" }}
APP_DEBUG=false
APP_ENV=production
APP_LOG_LEVEL=info
DB_CONNECTION=pgsql
DB_DRIVER=pgsql
DB_DATABASE=betting
DB_HOST=postgres.dsekt.internal
DB_PORT=5432
DB_USERNAME=betting
SSO_API_URL="http://sso.nomad.dsekt.internal"
LOGIN_API_URL="https://login.datasektionen.se"
LOGIN_FRONTEND_URL="https://login.datasektionen.se"
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
