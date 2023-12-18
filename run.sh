#!/bin/bash
set -e

if [ "$EUID" -ne 0 ]; then
	echo "Please run as root"
	exit 1
fi

systemctl start apache2 postgresql

# Install web files
cp -r src/* /var/www/html/

# Install database scripts
cp sql/*.sql /var/lib/postgresql/

if [ ! $1 ]; then
    su - postgres -c "psql -f setup.sql" > /dev/null
    echo "Use 'sudo ./run.sh <anything>' to show the full psql output"
else
    su - postgres -c "psql -f setup.sql"
fi

echo "Installed successfully. See http://localhost."
