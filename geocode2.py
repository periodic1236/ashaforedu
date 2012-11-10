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
  cursor.execute("SELECT DISTINCT district.id, district.name, state.name FROM district JOIN state ON (district.state_id = state.id) WHERE latitude IS NULL")
  rows = cursor.fetchall()
  for row in rows:
    geocoder = GoogleGeocoder()
    query = "%s, %s, India" % (row[1], row[2])
    try:
      search = geocoder.get(query)
    except ValueError, e:
      logging.info("FAILED %s - %s", row[0], e)
      continue
    location = search[0].geometry.location
    logging.info("Updating row %s - %s, %s", row[0], row[1], row[2])
    cursor.execute("UPDATE district SET latitude = %s, longitude = %s WHERE "
                   "id = %s" % (location.lat, location.lng, row[0]))
    time.sleep(0.05)


if __name__ == '__main__':
  main(sys.argv)