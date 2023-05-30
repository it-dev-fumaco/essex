# essex
Employee Support and Services Exchange (Employee Portal)

Instructions using GUI and Commands

    Download GitHub Desktop
    Clone Project https://github.com/it-dev-fumaco/essex.git
    Locate Project Folder on your local PC
    Open CMD and change directory to your Local project folder
    Type copy .env.example .env
    Setup database connections in .env file
         - For Dev
            DB_CONNECTION=mysql
            DB_HOST=10.0.49.72
            DB_PORT=3306
            DB_USERNAME=web
            DB_PASSWORD=fumaco
            
            DB_ESSEX=essex
            DB_ERP=_3f2ec5a818bccb73
            DB_KB=fumaco_knowledge_base
            LINK_KB=http://10.0.49.72:8085 # Knowledgebase URL
            MAIL_RECIPIENT=it@fumaco.local
            
            BASE_PATH="C:/xampp/htdocs/essex" # Project folder of essex, for artisan command
    Type php artisan key:generate
    Type php artisan optimize
    Type php artisan serve
    Access it via URL using your IP or localhost with default port = 8000
    Open VsCode
