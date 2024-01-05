import routeros_api
from os import system

# Database Information
DB_NAME = 'olt'
DB_USER = 'admin'
DB_PASSWORD = '123'


connection = routeros_api.RouterOsApiPool('host', username='username', password='password', port=port, plaintext_login=True)
api = connection.get_api()

interface = api.get_resource('/interface')
pppoeClient = []

for i in interface.get():
    if i['type'] == 'pppoe-in':
        pppoeClient.append(i['name'])

dataTraffic = []

for i in pppoeClient:
    data = api.get_binary_resource('/').call('interface/monitor-traffic', {'interface': f'{i}'.encode(), 'once': b'true'})
    dataTraffic.append([data[0]['name'].decode().replace('<', '').replace('>', '').replace('pppoe-', ''), data[0]['rx-bits-per-second'].decode(), data[0]['tx-bits-per-second'].decode()])



for i in dataTraffic:
    sql = f'''mysql -u{DB_USER} -p{DB_PASSWORD} -s -e "use {DB_NAME}; insert into traffic_data (username, rx, tx) VALUES ('{i[0]}', '{i[1]}', '{i[2]}')" '''
    sql2 = f'''mysql -u{DB_USER} -p{DB_PASSWORD} -s -e "use {DB_NAME}; insert into live_traffic (username, rx, tx) VALUES ('{i[0]}', '{i[1]}', '{i[2]}') on duplicate key update rx = values(rx), tx = values(tx)" '''
    system(sql)
    system(sql2)