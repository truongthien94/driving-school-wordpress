cd /var/www/dev.admin.sbs-ds.com/driving-school-wordpress
docker compose down
docker compose up -d
docker compose ps
curl -I http://127.0.0.1:8080


git stash -u      # lưu tạm cả file untracked
git pull --ff-only
git stash pop  

./scripts/deploy.sh
./scripts/deploy_stg.sh

cd wp-content/themes/sbs-portal/languages && msgfmt -o ja.mo ja.po && msgfmt -o en_US.mo en_US.po && msgfmt -o id_ID.mo id_ID.po && echo "Recompiled .mo files successfully"

msgfmt -o ja.mo ja.po && msgfmt -o en_US.mo en_US.po && msgfmt -o id_ID.mo id_ID.po && echo "Recompiled .mo files successfully"