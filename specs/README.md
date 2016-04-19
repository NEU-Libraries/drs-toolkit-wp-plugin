# UNIT TESTS

## SETUP

* Install nodejs & symlink node

```
sudo apt-get update

sudo apt-get install nodejs

sudo ln -s /usr/bin/nodejs /usr/bin/node
```

* Install npm

```
sudo apt-get install npm
```

* Install Grunt CLI

```
 npm install -g grunt-cli
```

* Install project libraries

```
cd /path/to/wordpress/wp-content/plugins/drs-tk/
npm install
```

## Run the jasmine tests

```
grunt jasmine
```
