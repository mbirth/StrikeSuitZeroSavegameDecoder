#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import struct
import json

# can be found in ~/.local/share/Steam/userdata/119879364/209540/remote
filename = "main.sav"
key = bytearray("tkileyismoarawesome!!!!citationneede", "utf-8")

data = bytearray(open(filename, "rb").read())

header = data[0:40]
crypt  = data[40:]

f = open("main_header.sav", "wb")
f.write(header)
f.close()

version = header[32:36]
version_num = struct.unpack("<L", version)
print("Version: %s" % version_num[0])

length = header[36:40]
length_num = struct.unpack("<L", length)
print("Payload length: %s Bytes" % length_num[0])

output = bytearray()
for i in range(0, len(crypt)):
    keyidx = i % len(key)

    keychar = key[keyidx]
    char    = crypt[i]

    newchar = char ^ keychar

    output.append(newchar)

f = open("main_data.sav", "wb")
f.write(output)
f.close()

json_data = json.loads(str(output, "utf-8"))

json_pretty = json.dumps(json_data, indent=4)

f = open("main_data.sav", "w")
f.write(json_pretty)
f.close()
