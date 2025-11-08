#!/bin/bash

# ===============================================
#   SCRIPT DE RESPALDO AUTOMÁTICO - NebulaNetwork
# ===============================================

# --- CONFIGURACIÓN BÁSICA ---
DB_NAME="nebulanetwork" # Nombre de la base de datos
DB_USER="root" # Usuario de la base de datos
DB_PASS="" # Contraseña 
BACKUP_PATH="/nebulanetwork/backups"
NOW=$(date +"%Y-%m-%d_%H-%M")

# --- CREAR DIRECTORIO SI NO EXISTE ---
mkdir -p "$BACKUP_PATH"

# --- GENERAR BACKUP ---
DUMP_FILE="$BACKUP_PATH/respaldo_$NOW.sql"
ARCHIVE_FILE="$BACKUP_PATH/respaldo_$NOW.tar.gz"

mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$DUMP_FILE"

# --- CONTAR TABLAS RESPALDADAS ---
TABLE_COUNT=$(grep -c "CREATE TABLE" "$DUMP_FILE")

# --- COMPRIMIR ARCHIVO SQL ---
tar -czf "$ARCHIVE_FILE" -C "$BACKUP_PATH" "$(basename "$DUMP_FILE")"

# --- OBTENER TAMAÑO DEL ARCHIVO COMPRIMIDO ---
BACKUP_SIZE=$(du -h "$ARCHIVE_FILE" | awk '{print $1}')

# --- RESULTADOS ---
echo "=============================="
echo "      RESPALDO COMPLETADO"
echo "=============================="
echo "Base de datos: $DB_NAME"
echo "Tablas exportadas: $TABLE_COUNT"
echo "Tamaño del archivo: $BACKUP_SIZE"
echo "Ruta: $ARCHIVE_FILE"
echo "Fecha: $NOW"
echo "=============================="

# ===============================================
#   PROGRAMACIÓN AUTOMÁTICA CON CRON
# ===============================================

# Para programar este script:
# 1. Ejecutar en consola:
#    crontab -e
#
# 2. Agregar la siguiente línea:
#    0 2 1 */3 * /ruta/al/script/respaldo_nebulanetwork.sh
#
#    Esto ejecutará el script el primer día de enero, abril,
#    julio y octubre, a las 2:00 AM.
#
# 3. Para verificar la programación:
#    crontab -l
# ===============================================