#!/usr/bin/env python
#
# Copyright 2012 Steady State. All Rights Reserved.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

import logging
import os
import re
import sys
import time

from googlegeocoder import GoogleGeocoder
import MySQLdb


def main(unused_argv):
  logging.basicConfig(level=logging.INFO)
  conn = MySQLdb.connect(host="mysql.anjoola.com", user="anjoola",
                         passwd="pokemon", db="ashaforedu")
  cursor = conn.cursor()
  cursor.execute("SELECT DISTINCT id, name FROM town WHERE latitude IS NULL")
  rows = cursor.fetchall()
  for row in rows:
    geocoder = GoogleGeocoder()
    try:
      search = geocoder.get("%s, India" % row[1])
    except ValueError, e:
      logging.info("FAILED %s - %s", row[0], e)
      continue
    name = re.sub(r"'", r"\'", row[1])
    location = search[0].geometry.location
    logging.info("Updating row %s - %s", row[0], name)
    cursor.execute("UPDATE town SET latitude = %s, longitude = %s WHERE "
                   "name = '%s'" % (location.lat, location.lng, name))
    time.sleep(0.05)


if __name__ == '__main__':
  main(sys.argv)