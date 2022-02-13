# pediatric-module
Project to children medic to store and update their patients data.

---- Connection config file.
You need update the db-properties.php file to put your own database connection information.

---- Config your own image.
You need to put your logo inside the path assets/logo.png with height and width to 284px.

---- Scripts for DB.
All DDL scripts are in the path conf/sql/DDL.sql to execute them in MySQL Workbench, besides inside of that path you will see another file named sp_administrar_paciente.sql, this contain the store procedure to manage patients.

---- Debugger 
If you need a debugger, you can use debugger() funcition inside of php files to write on SystemErr log.
