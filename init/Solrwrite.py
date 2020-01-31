import pysolr
solr = pysolr.Solr('http://localhost:8983/solr/EELAB1',timeout=100)

import pymysql

conn = pymysql.connect( host='127.0.0.1',
                        port=3306,
                        user='root',
                        passwd='',
                        charset='utf8',
                        db='EELAB1',)
crusor = conn.cursor()

get_ConferenceName_fromID = 'SELECT `ConferenceName` FROM `conferences` WHERE `ConferenceID` = %s'
get_AuthorID = 'SELECT `AuthorID` FROM `paper_author_affiliation` WHERE `PaperID` = %s ORDER BY AuthorSequence ASC'
get_AuthorName_fromID = 'SELECT `AuthorName` FROM `authors` WHERE `AuthorID` = %s'


solr.delete(q='*:*')
solr.commit()
f = open('data/papers.txt','r', encoding='UTF-8')
index = 0
while True:
    line = f.readline()
    if len(line) == 0 or len(line) ==1:
        break
    data = line[:-1].split('\t')
    PaperID = data[0]
    PaperName = data[1]
    Year = data[2]
    ConferenceID = data[3]
    crusor.execute(get_ConferenceName_fromID,ConferenceID)
    result = crusor.fetchall()
    ConferenceName = result[0][0]
    crusor.execute(get_AuthorID,PaperID)
    result = crusor.fetchall()
    AuthorIDlis = []
    AuthorNamelis = []
    for (AuthorID0) in result:
        AuthorIDlis += [AuthorID0]
        crusor.execute(get_AuthorName_fromID, AuthorID0)
        result = crusor.fetchall()
        AuthorNamelis += [result[0][0]]
    index += 1
    solr.add([
        {
            "PaperID": PaperID,
            "PaperName": PaperName,
            "AuthorID": AuthorIDlis,
            "AuthorName": AuthorNamelis,
            "ConferenceName": ConferenceName,
            "ConferenceID": ConferenceID,
            "Year": Year,
        },
    ])
    if not (index % 1000):
        solr.commit()
        print (index)
solr.commit()
f.close()
