import pandas as pd
import glob
import pymysql

connection = pymysql.connect(host='kidup-production.c5gkrouylqmw.eu-central-1.rds.amazonaws.com',
                             user='kidup_148161251',
                             password='knop0602R',
                             db='kidup',
                             charset='utf8mb4',
                             cursorclass=pymysql.cursors.DictCursor)
df = pd.read_sql_query("""select * from tracking_event WHERE (user_id not in (1,4,7,142,3940) or user_id is null)
and language not like '%nl%'
and ip is not null
and session is not null
and created_at > 1449319602""", connection)

df.to_hdf("filtered_data.hdf", "df1")
