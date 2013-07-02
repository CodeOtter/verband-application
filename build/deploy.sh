cd build
rsync -pcrlzvi -e 'ssh' --chmod=u=rwx --filter='merge rsync.filter' ../* $1@$1.com:/home/$1/public_html/
ssh $1@$1.com << EOF
  rm -fr public_html/application/Cache/*
  chmod 0770 public_html/application/Cache
  php application/Console.php migrations:migrate --configuration=/home/$1/public_html/migrations.yml --no-interaction --verbose
  php application/Console.php orm:generate-proxies
EOF