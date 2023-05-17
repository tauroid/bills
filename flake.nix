{
  description = "Bills thing";

  inputs = {
    nixpkgs.url = "nixpkgs/nixos-unstable";
    nixos-generators = {
      url = "github:nix-community/nixos-generators";
      inputs.nixpkgs.follows = "nixpkgs";
    };
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, nixos-generators, flake-utils }:
    let systems = ["x86_64-linux" "x86_64-darwin" "aarch64-linux"];
    in flake-utils.lib.eachSystem systems (system:
      let pkgs = nixpkgs.legacyPackages.${system};
          dev-drv = pkgs.mkShell {
            buildInputs = [
              pkgs.php
              pkgs.php.packages.composer
              pkgs.yarn
              pkgs.nodejs
            ];
            LD_LIBRARY_PATH = "${pkgs.lib.makeLibraryPath [
              pkgs.glib
              pkgs.nss
              pkgs.nspr
              pkgs.at-spi2-core
              pkgs.xorg.libX11
              pkgs.xorg.libXcomposite
              pkgs.xorg.libXdamage
              pkgs.xorg.libXext
              pkgs.xorg.libXfixes
              pkgs.xorg.libXrandr
              pkgs.xorg.libxcb
              pkgs.cups
              pkgs.libdrm
              pkgs.dbus
              pkgs.mesa
              pkgs.expat
              pkgs.libxkbcommon
              pkgs.pango
              pkgs.cairo
              pkgs.alsa-lib
            ]}";
          };
          onDarwin = pkgs.lib.strings.hasInfix "darwin" system;
          arch = builtins.elemAt (pkgs.lib.strings.split "-" system) 0;
      in {
        packages.default = pkgs.symlinkJoin {
          name = "devServer";
          paths = [
            (pkgs.writeScriptBin "devServer"
              "nix develop --command bash -c \"\(trap \'kill 0\' SIGINT; php artisan serve & yarn dev & wait\)\"")
          ];
        };
        packages.oldImage = nixos-generators.nixosGenerate {
          system = "${system}";
          modules = [
            ({ config, lib, pkgs, ... }:
              {
                system.stateVersion = "23.05";
                users.users.dev = {
                  isNormalUser = true;
                  home = "/home/dev";
                  description = "User for the dev environment";
                };
              })
          ];
          format = "docker";
        };
        packages.image =
          pkgs.dockerTools.buildLayeredImage {
            name = "bills-dev-server";
            tag = "latest";
            contents = [
              pkgs.bashInteractive
              pkgs.coreutils
              pkgs.su
              pkgs.screen
              pkgs.php
              pkgs.php.packages.composer
              pkgs.yarn
              pkgs.nodejs
              pkgs.gnused
              pkgs.mariadb
              pkgs.socat
              pkgs.inotify-tools
              pkgs.diffutils
            ];
            extraCommands = ''
              mkdir tmp
              chmod 777 tmp
              mkdir usr
              ln -s /bin usr/bin
              mkdir -p home/dev
              mkdir -p run/screens
              chmod 777 -R run/screens
              mkdir root
            '';
            config =
              let mysqlStartup = pkgs.writeScriptBin "mysqlStartup" ''
                    # thanks to https://jeancharles.quillet.org/posts/2022-01-30-Local-mariadb-server-with-nix-shell.html
                    if [ "$1" == "--as-root" ]; then export USER_ROOT_ARG="--user=root"; fi
                    if [ ! -d "$MYSQL_HOME" ]; then
                      mysql_install_db --auth-root-authentication-method=normal \
                        --datadir=$MYSQL_DATADIR --basedir=$MYSQL_BASEDIR \
                        --pid-file=$MYSQL_PID_FILE
                    fi

                    mysqld --datadir=$MYSQL_DATADIR --pid-file=$MYSQL_PID_FILE \
                            --socket=$MYSQL_UNIX_PORT $USER_ROOT_ARG
                  '';
                  mysqlWatch = pkgs.writeScriptBin "mysqlWatch" ''
                    DOCKER_HOST_IP=172.17.0.1

                    while ! socat -u OPEN:/dev/null UNIX-CONNECT:$MYSQL_UNIX_PORT 2> /dev/null; do
                      sleep 0.2
                    done

                    source .env
                    mysql -u root --execute="DROP USER '$DB_USERNAME'@'localhost';"
                    mysql -u root --execute="DROP USER '$DB_USERNAME'@'$DOCKER_HOST_IP';"
                    mysql -u root --execute="DROP DATABASE $DB_DATABASE;"
                    mysql -u root --execute="CREATE DATABASE $DB_DATABASE;"
                    mysql -u root $DB_DATABASE < db.sql
                    mysql -u root --execute="CREATE USER '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
                    mysql -u root --execute="CREATE USER '$DB_USERNAME'@'$DOCKER_HOST_IP' IDENTIFIED BY '$DB_PASSWORD';"
                    mysql -u root --execute="GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'localhost';"
                    mysql -u root --execute="GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'$DOCKER_HOST_IP';"

                    touch "$MYSQL_HOME/db_created"

                    inotifywait -e close_write,moved_to,create -m . |
                    while read -r directory event filename; do
                      if [ "$filename" == "db.sql" ] || [ "$filename" == ".env" ]; then
                        while ! mkdir "$MYSQL_HOME/db.lock"; do sleep 0.5; done
                        # skip if a dump just happened
                        if [ -f "$MYSQL_HOME/dumped" ]; then
                          rm "$MYSQL_HOME/dumped"
                          rm -r "$MYSQL_HOME/db.lock"
                          continue
                        fi

                        echo "Reloading DB.."
                        OLD_DB_DATABASE=$DB_DATABASE
                        OLD_DB_USERNAME=$DB_USERNAME
                        OLD_DB_PASSWORD=$DB_PASSWORD
                        source .env

                        if [ "$DB_USERNAME" != "$OLD_DB_USERNAME" ] || \
                            [ "$DB_PASSWORD" != "$OLD_DB_PASSWORD" ]; then
                          mysql -u root --execute="DROP USER '$OLD_DB_USERNAME'@'localhost';"
                          mysql -u root --execute="DROP USER '$OLD_DB_USERNAME'@'$DOCKER_HOST_IP';"
                        fi
                        if [ "$DB_DATABASE" != "$OLD_DB_DATABASE" ]; then
                          mysql -u root --execute="DROP DATABASE $OLD_DB_DATABASE;"
                          mysql -u root --execute="CREATE DATABASE $DB_DATABASE;"
                        fi
                        mysql -u root $DB_DATABASE < db.sql
                        if [ "$DB_USERNAME" != "$OLD_DB_USERNAME" ] || \
                            [ "$DB_PASSWORD" != "$OLD_DB_PASSWORD" ]; then
                          mysql -u root --execute="CREATE USER '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
                          mysql -u root --execute="CREATE USER '$DB_USERNAME'@'$DOCKER_HOST_IP' IDENTIFIED BY '$DB_PASSWORD';"
                        fi
                        if [ "$DB_USERNAME" != "$OLD_DB_USERNAME" ] || \
                            [ "$DB_PASSWORD" != "$OLD_DB_PASSWORD" ] || \
                            [ "$DB_DATABASE" != "$OLD_DB_DATABASE" ]; then
                          mysql -u root --execute="GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'localhost';"
                          mysql -u root --execute="GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'$DOCKER_HOST_IP';"
                        fi

                        rm -r "$MYSQL_HOME/db.lock"
                      fi
                    done
                  '';
                  mysqlDump = pkgs.writeScriptBin "mysqlDump" ''
                    #while ! socat -u OPEN:/dev/null UNIX-CONNECT:$MYSQL_UNIX_PORT 2> /dev/null; do
                    while ! [ -f "$MYSQL_HOME/db_created" ]; do sleep 0.5; done

                    rm "$MYSQL_HOME/db_created"

                    while true; do
                      while ! mkdir "$MYSQL_HOME/db.lock"; do sleep 0.5; done

                      source .env
                      NEW_SQL=$(mysqldump -u root "$DB_DATABASE")
                      NEW_SQL=$(sed '$d' <<< "$NEW_SQL")
                      if [ "$(cat db.sql)" != "$NEW_SQL" ]; then
                        echo "Saving DB change.."
                        # so that the watcher doesn't load dumps
                        # (has the downside that db file edits between dump
                        # and next watch trigger get ignored and overwritten)
                        touch "$MYSQL_HOME/dumped"
                        echo "$NEW_SQL" > db.sql
                      fi

                      rm -r $MYSQL_HOME/db.lock
                      sleep 1
                    done
                  '';
                  env = pkgs.writeText ".env" ''
                    APP_NAME=Laravel
                    APP_ENV=local
                    #APP_KEY=
                    APP_DEBUG=true
                    APP_URL=http://localhost

                    LOG_CHANNEL=stack
                    LOG_DEPRECATIONS_CHANNEL=null
                    LOG_LEVEL=debug

                    DB_CONNECTION=mysql
                    DB_HOST=0.0.0.0
                    DB_PORT=3306
                    DB_DATABASE=bills
                    DB_USERNAME=bills
                    #DB_PASSWORD=

                    BROADCAST_DRIVER=log
                    CACHE_DRIVER=file
                    FILESYSTEM_DISK=local
                    QUEUE_CONNECTION=sync
                    SESSION_DRIVER=file
                    SESSION_LIFETIME=120

                    MEMCACHED_HOST=127.0.0.1

                    REDIS_HOST=127.0.0.1
                    REDIS_PASSWORD=null
                    REDIS_PORT=6379

                    MAIL_MAILER=smtp
                    MAIL_HOST=mailhog
                    MAIL_PORT=1025
                    MAIL_USERNAME=null
                    MAIL_PASSWORD=null
                    MAIL_ENCRYPTION=null
                    MAIL_FROM_ADDRESS="hello@example.com"
                    MAIL_FROM_NAME="''${APP_NAME}"

                    AWS_ACCESS_KEY_ID=
                    AWS_SECRET_ACCESS_KEY=
                    AWS_DEFAULT_REGION=us-east-1
                    AWS_BUCKET=
                    AWS_USE_PATH_STYLE_ENDPOINT=false

                    PUSHER_APP_ID=
                    PUSHER_APP_KEY=
                    PUSHER_APP_SECRET=
                    PUSHER_HOST=
                    PUSHER_PORT=443
                    PUSHER_SCHEME=https
                    PUSHER_APP_CLUSTER=mt1

                    VITE_PUSHER_APP_KEY="''${PUSHER_APP_KEY}"
                    VITE_PUSHER_HOST="''${PUSHER_HOST}"
                    VITE_PUSHER_PORT="''${PUSHER_PORT}"
                    VITE_PUSHER_SCHEME="''${PUSHER_SCHEME}"
                    VITE_PUSHER_APP_CLUSTER="''${PUSHER_APP_CLUSTER}"
                  '';
                  initFile = pkgs.writeScriptBin "initFile" ''
                    ${pkgs.dockerTools.shadowSetup}
                    HOST_GID=$(stat -c '%g' composer.json)
                    HOST_UID=$(stat -c '%u' composer.json)

                    ENV=/home/dev/bills/.env

                    cp ${env} $ENV

                    echo "APP_KEY=base64:$(${pkgs.openssl}/bin/openssl rand -base64 32)" >> $ENV
                    echo "DB_PASSWORD=$(${pkgs.openssl}/bin/openssl rand -base64 32)" >> $ENV

                    groupadd -r -g "$HOST_GID" dev
                    useradd -r -u "$HOST_UID" -g "$HOST_GID" dev
                    chown "$HOST_UID:$HOST_GID" /home/dev

                    chmod 755 /home/dev/bills/.env
                    cat << 'EOSU' > .profile
                    cd /home/dev/bills

                    YELLOW="\033[1;93m"
                    BLUE="\033[1;36m"
                    MAGENTA="\033[1;95m"
                    CYAN="\033[1;96m"
                    GREEN="\033[1;92m"
                    WHITE="\033[1;97m"
                    NC="\033[0m"
                    ulimit -n 32186
                    screen -d -m -S laravel bash -c "composer install ; php artisan serve --host 0.0.0.0"
                    printf "Launched ''${YELLOW}laravel''${NC}, view using \`screen -r laravel\`\n"
                    screen -d -m -S vite bash -c "yarn ; yarn dev --host 0.0.0.0"
                    printf "Launched ''${BLUE}vite''${NC} via \`yarn dev\`, view using \`screen -r vite\`\n"

                    export MYSQL_BASEDIR=${pkgs.mariadb}
                    export MYSQL_HOME=/home/dev/.mariadb
                    export MYSQL_DATADIR=$MYSQL_HOME/data
                    export MYSQL_UNIX_PORT=$MYSQL_HOME/mysql.sock
                    export MYSQL_PID_FILE=$MYSQL_HOME/mysql.pid
                    alias mysql='mysql -u root'

                    # in mac the folder belongs to root user so whatever
                    # just don't run prod on a mac I guess
                    # also why are you running prod from this image anyway
                    if [ "$UID" == "0" ]; then export AS_ROOT_IF_ROOT="--as-root"; fi

                    screen -d -m -S mariadb bash ${mysqlStartup}/bin/mysqlStartup $AS_ROOT_IF_ROOT
                    printf "Launched ''${MAGENTA}mariadb''${NC}, view using \`screen -r mariadb\`\n"

                    if [ -f "$MYSQL_HOME/db_created" ]; then
                      rm "$MYSQL_HOME/db_created"
                    fi

                    if [ -d "$MYSQL_HOME/db.lock" ]; then
                      rm -r "$MYSQL_HOME/db.lock"
                    fi

                    screen -d -m -S mariadb-watch bash ${mysqlWatch}/bin/mysqlWatch
                    printf "Launched ''${CYAN}mariadb-watch''${NC}, view using \`screen -r mariadb-watch\`\n"

                    screen -d -m -S mariadb-dump bash ${mysqlDump}/bin/mysqlDump
                    printf "Launched ''${GREEN}mariadb-dump''${NC}, view using \`screen -r mariadb-dump\`\n"

                    printf "''${WHITE}Detach from screens using \`ctrl-a d\`''${NC}\n"
                    EOSU
                    USERNAME=$(id -un $HOST_UID)
                    mv .profile "$(eval echo "~$USERNAME")/"
                    su - "$USERNAME"
                    exit
                  ''; in {
                Entrypoint = [ "/bin/bash" "-c" "${initFile}/bin/initFile" ];
                ExposedPorts = {
                  "8000" = {};
                  "5173" = {};
                };
                Volumes = {
                  "/home/dev/bills" = {};
                };
                WorkingDir = "/home/dev/bills";
              };
          };

        apps.build =
          let img = pkgs.dockerTools.buildLayeredImage {
                name = "bills-build-environment";
                tag = "latest";
                contents = [
                  pkgs.coreutils
                  pkgs.bash
                  pkgs.yarn
                  pkgs.nodejs
                  pkgs.php
                  pkgs.php.packages.composer
                  pkgs.unzip
                ];
                extraCommands = ''
                  mkdir usr
                  ln -s /bin usr/bin
                '';
                config = {
                  Entrypoint =
                    let script = pkgs.writeScriptBin "script" ''
                      yarn
                      yarn build
                      composer install --optimize-autoloader --no-dev
                      php artisan config:cache
                    '';
                    in [ "/bin/bash" "-c" "${script}/bin/script" ];
                  Volumes = {
                    "/repo" = {};
                  };
                  WorkingDir = "/repo";
                };
              };
              drv = pkgs.writeScriptBin "build" ''
                docker load -i ${img}
                docker run --rm -v=$(pwd):/repo bills-build-environment:latest
              '';
          in {
          type = "app";
          program = "${drv}/bin/build";
        };

        # on osx follow these instructions:
        # https://nixos.org/manual/nixpkgs/unstable/#sec-darwin-builder
        # with the second dash after ${MAX_JOBS} replaced with kvm
        # then switch docker to use VirtioFS so inotify works
        # then use nix develop as normal
        devShells.default = pkgs.mkShell {
          shellHook = ''
            if [ "$BILLS_HTTP_PORT" == "" ]; then export BILLS_HTTP_PORT=8000; fi
            if [ "$BILLS_VITE_PORT" == "" ]; then export BILLS_VITE_PORT=5173; fi
            if [ "$BILLS_MARIADB_PORT" == "" ]; then export BILLS_MARIADB_PORT=3306; fi
            docker load -i ${self.packages.${arch + "-linux"}.image}
            docker run -ti --rm -v=$(pwd):/home/dev/bills \
              -p "$BILLS_HTTP_PORT:8000" \
              -p "$BILLS_VITE_PORT:5173" \
              -p "$BILLS_MARIADB_PORT:3306" \
              bills-dev-server:latest
            exit
          '';
        };
      }
    );
}
