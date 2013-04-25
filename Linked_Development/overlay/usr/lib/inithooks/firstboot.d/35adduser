#!/bin/bash
# Adds non root user for ssh access.

# create user "linked-data" with home directory /home/linked-data and bash shell
/usr/sbin/useradd linked-data -m -s /bin/bash

# lock the "linked-data" account until a password is set
/usr/bin/passwd linked-data -l

# prompt user for password - todo can we write text in the prompt explaing what the password is for?
/usr/lib/inithooks/bin/setpass.py linked-data

# unlock the "linked-data" account now the password has been set
/usr/bin/passwd linked-data -u
