# 2.0 (2016-08-30)

## Core update

- **silex**: now startsilex is compatible with silex 2.0

# 1.3.3 (2016-04-21)

## New features

- **php**: full compatibility with php 7.0

# 1.3.2 (2016-02-24)

## New features

- **entities**: console entities:generate stub (generates entities from db tables)

## Breaking changes

- **support**: dropped support to php 5.4

# 1.3.1 (2015-06-11)

## New features

- **symfony**: added app/console capability (see Services/Command for a usage example)


## Bug Fixes

- **symfony**: removed deprecated istruction _method in routes.yml


# 1.3.0 (2015-06-09)

## New features

- **silex**: version 1.3
- **symfony**: works with 2.7 LTS
- **scaffold**: now libs is "src" and it is based on a new standard structure (Controller/ Entity/ Services/)
- **modelHelpers**: added a entityBuilder and some abstraction for mappers and entities


## Bug Fixes

- **monolog**: fixed .gitignore for development.log
- **monolog**: fixed boring development.log permission error (see composer.json)

