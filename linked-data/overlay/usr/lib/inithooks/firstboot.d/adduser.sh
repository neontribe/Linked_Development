#!/bin/bash
# Adds non root user for ssh access.
useradd linked-data -m -s /bin/bash
/usr/lib/inithooks/bin/setpass.py linked-data
