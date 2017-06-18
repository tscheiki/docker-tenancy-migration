How does advanced container technology provide an additional security level for multi-tenant solutions?
-

The protection of private information has never been a higher priority. 
ISO 27018, the code of practice for protection of personally identifiable 
information in public clouds aims to address this concern. 
In this essay, we will analyze how the security environment for a 
multi-tenant public cloud enabled SaaS offering is being 
enhanced by modern container technology compared to a 
traditional multi-tenant approach on the application level. 
Potential risks and security requirements for both 
alternatives are mapped against ISO 27018 requirements
based on the controls domain grouping of the cloud controls matrix (CCM). 
This should support public cloud service providers to obey to relevant 
responsibilities when acting as a processor for Personally Identifiable 
Information (PII) and support them in identifying the right technology stack 
for PII. Our use case is based on a traditional multi-tenant approach that 
combines a MySQL database and a web server which is hosted in the cloud. 
The alternative enhanced use case is based on a Docker container-engine 
where each client obtains a separate database and web server set-up in the cloud.

Whitepaper
-
Link to whitepaper will follow soon.

Demo
-
[![IMAGE ALT TEXT](http://img.youtube.com/vi/10YUTf3MMu0/0.jpg)](https://www.youtube.com/watch?v=10YUTf3MMu0 "Migration from a traditional multi-tenant solution to container based solution")
 
How does it work?
-
1. Open traditional multi-tenant solution (clone this repository, setup vHost, create DB)
2. Click on "Start Docker Migration"  
3. Database data for this client gets stored in a dump file located at ```migration/dumps/data.sql``` (Export order and relations are defined in ```migration/createMigration.php```)  
4. Ansible playbook gets started ```migration/ansible/migrationPlaybook.yml```  
4.1 Copies needed files (scripts, dump) to remote machine that is defined in ```migration/ansible/hosts```   
4.2 Starts script ```migration/core/app/scripts/newClient.sh``` on remote machine  
4.2.1 Docker container gets created  
4.2.2 MySQL Server gets created  
4.2.3 Dump gets inserted  
4.2.4 Link to client container gets set in nginx config
5. Container starts and returns new client url

**Technologies and Frameworks used:**

- PHP
- Ansible
- Docker
- MySQL
- Bootstrap
- jQuery

**Contributors:**

- Roland Bole
- Andree Niebuhr
- Markus Tscheik