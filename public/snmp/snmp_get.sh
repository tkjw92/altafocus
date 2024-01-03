HOST=$1
COMUNITY=$2
VERSION=$3
OID=$4

snmpwalk -c "$COMUNITY" -v "$VERSION" "$HOST" "$OID" > data.txt