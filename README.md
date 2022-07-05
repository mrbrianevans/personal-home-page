# Personal Home Page
Personal website for Brian Evans written in PHP.

[![wakatime](https://wakatime.com/badge/user/f8dd9b3d-8b67-421e-8f3e-ab941b402e60/project/a84c9adc-2a21-4c8f-8c95-3f88ff781dc8.svg)](https://wakatime.com/badge/user/f8dd9b3d-8b67-421e-8f3e-ab941b402e60)

## Technology
The main site is written in PHP. 
MySQL database is used to persist data.
Some minimal JavaScript is used for client site reactivity.
All styling is done in pure CSS with no external libraries.


## File structure
The `db` folder contains the MySQL database schema, and the `www` folder contains the public PHP and static files.

## Local development
The site can be worked on locally by running it in Docker.
```powershell
docker compose up -d --build
```

## Commit history
This site began closed source, and after about 2 years of running it, I published the source code to GitHub. 
As a result, the Git commit history is missing from the first 2 years, which made up most of the development.
