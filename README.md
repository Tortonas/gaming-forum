# Gaming forum
![github-large](readme.gif)
This is a simple forum with its categories, topics, gallery and admin panel. This project was made in 2019.

# Developer team

**Valentinas Kasteckis** (forum sub-system)

**Henrikas Juzuitis** (gallery sub-system)

**Eligijus Kiudys** (admin sub-system)

**Rimvydas Neverauskas** (user sub-system)

The project was carried out by a team of 4 people.

# Run this project locally
1. Have `docker` and `Makefile` installed
2. Clone this repository
3. `$ make prepare`
4. `$ make prepare-db`
5. visit `localhost:80`

# SQL Injection
This project has many issues, one of them you can perform SQL injection.  
You can run the following command in order to get access to database:  
`$ sqlmap http://localhost/themes.php?id=1 --tables`


# What has been used

1. Bootstrap
2. Plain PHP 7.4
3. MySQL
