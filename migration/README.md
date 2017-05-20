Simple To-Do App Migration
-

Migrate a PHP application and its relational database to a container based application with Ansible.

**How to get this migration running?**

tbd

**Next Steps**

1. Ubuntu Maschine vorbereiten
/app/
    clients/
    repo/
        fh-todo/index.php #GIT Repo der App clonen

2. Ansible starten

3. Migration-DB-Skript starten => SQL-Dumps erstellt

4. Mapping überlegen - company => subdomain + IP-Adresse

5. Dateien kopieren
    clients/
        /subdomain/docker.compose
        /subdomain/site.conf

6. Pro Client werden Container gestartet

7. Datenbank Dump einspielen

8. Python necessary: sudo apt-get -y install python-simplejson

**Technologies and Frameworks used:**

- PHP
- Ansible