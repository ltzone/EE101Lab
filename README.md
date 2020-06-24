# EE101Lab

Lab for EE101 is a website which allows users to search for papers and learn more about the details through the webpage and visualized charts. The website is based on MySQL database, Solr search engine and PHP. Echarts and Gephi are applied in visualization. The project was completed with Shaoheng Fang, Shiwen Dong, Hongbo Yang in 2019 Spring.

## Demo

See https://acemap.ltzhou.com

## Report

See https://github.com/ltzone/EE101Lab/blob/master/report/report.pdf

## CheckList before you deploy this project

1. Install MySQL & Solr(>8.0.0)
2. Configure MySQL
   - create a schema and authenticated user for the project
   - run init/SQLwrite.py, note that some settings should be modified according to the previous step
3. Configure Solr
   - install java
   - run Solr on your JVM
   - Copy `~\solr-8.0.0\solr-8.0.0\server\solr\configsets\_default` to `~\solr-8.0.0\solr-8.0.0\server\solr\new_core` which is just created.
   - login to the 8983 port in your browser, create a `new_core`, add the following schemas in the core
FieldName	|Type	|Indexed	|Stored 	|Multi-valued	|Required
---|--|--|--|--|--
PaperID 	|string 	|Y 	|Y 	|N 		|Y
PaperName 	|text en 	|Y 	|Y 	|N 		|Y
AuthorID 	|string 	|Y 	|Y 	|Y 		|Y
AuthorName 	|text en 	|Y 	|Y 	|Y 		|Y
ConferenceID 	|string 	|Y 	|Y 	|N 		|Y
ConferenceName 	|text en 	|Y 	|Y 	|N 		|Y
Year 		|string 	|Y 	|Y 	|N 		|Y
keyword	| text_en | Y |Y |Y |N 

    - add copy field (3 times)
      - source: PaperName  destination: keyword
      - source: AuthorName  destination: keyword
      - source: ConferenceName  destination: keyword
    - run `init/SolrWrite.py`

4. Configure PHP
   - install PHP on your server
   - copy all the files in the directory into `/var/www/html`
   - configure your `httpd.conf`
   - important: modify the mysql connection settings in every php file in the `page` directory!
   - enjoy!
