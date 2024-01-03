from os import system

# initiate variable value
# host
host = '192.168.95.2'
# comunity
comunity = 'altafocus'
# version snmp
version = '2c'

# Database Information
db_host = '127.0.0.1'
db_user = 'admin'
db_password = '123'
db_name = 'olt'
tb_name = 'data'


# Optical Power separated
optSeparated = '-2147483648'


# get snmp oid data client names
# oid start client names
startClientNames = ['iso.3.6.1.2.1.2.2.1.2', 13]
clientNames = []
system(f'./snmp_get.sh {host} {comunity} {version} {startClientNames[0]}')
data = open('data.txt').readlines()

for i in data:
    if int(i.replace('iso.', '').replace(' STRING:', '').replace('\n', '').split(' = ')[0].split('.')[-1]) >= startClientNames[1]:
        clientNames.append(i.replace('iso.', '').replace(' STRING:', '').replace('\n', '').split(' = ')[1].replace('"', ''))




# get snmp oid data mac clients
# oid start mac clients
startClientMac = ['.1.3.6.1.2.1.2.2.1.6', 13]
clientMac = []
system(f'./snmp_get.sh {host} {comunity} {version} {startClientMac[0]}')
data = open('data.txt').readlines()

for i in data:
    if int(i.replace('iso.', '').replace(' Hex-STRING:', '').replace('\n', '').split(' = ')[0].split('.')[-1]) >= startClientMac[1]:
        clientMac.append(i.replace('iso.', '').replace(' Hex-STRING:', '').replace('\n', '').split(' = ')[1].strip().replace(' ', ':'))



# get snmp oid data optical power
# oid start client optical power
startClientPower = '1.3.6.1.4.1.50224.3.3.3.1.4'
clientPower = []
system(f'./snmp_get.sh {host} {comunity} {version} {startClientPower}')
data = open('data.txt').readlines()

for i in data:
    if optSeparated in i.replace('iso.', '').replace(' INTEGER:', '').replace('\n', ''):
        continue
    clientPower.append('{:.2f}'.format(float(i.replace('iso.', '').replace(' INTEGER:', '').replace('\n', '').split(' = ')[1])/100))




# get snmp oid data distance client
# oid start client distance
startClientDistances = '.1.3.6.1.4.1.50224.3.3.2.1.15'
clientDistances = []
system(f'./snmp_get.sh {host} {comunity} {version} {startClientDistances}')
data = open('data.txt').readlines()

for i in data:
    clientDistances.append(i.replace('iso.', '').replace(' INTEGER:', '').replace('\n', '').split(' = ')[1])



# append all data in one string
for i in range(len(clientNames)):
    sql = f'''mysql -u{db_user} -p{db_password} -s -e "use {db_name}; insert into {tb_name} (name, mac, distance, power) VALUES ('{clientNames[i]}', '{clientMac[i]}', '{clientDistances[i]}', '{clientPower[i]}')"'''
    sql2 = f'''mysql -u{db_user} -p{db_password} -s -e "use {db_name}; insert into live (name, mac, distance, power) VALUES ('{clientNames[i]}', '{clientMac[i]}', '{clientDistances[i]}', '{clientPower[i]}') on duplicate key update power = VALUES(power)"'''
    system(sql)
    system(sql2)
