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
import time

from googlegeocoder import GoogleGeocoder
import MySQLdb
import webapp2


class DBHandler(webapp2.RequestHandler):
  def get(self):
    conn = MySQLdb.connect(host="mysql.anjoola.com", user="anjoola",
                           passwd="pokemon", db="ashaforedu")
    cursor = conn.cursor()
    cursor.execute("SELECT DISTINCT id, name FROM town WHERE latitude IS NULL")
    rows = cursor.fetchall()
    self.response.write("%s rows returned.<br>\n" % len(rows))
    for row in rows:
      geocoder = GoogleGeocoder()
      try:
        search = geocoder.get("%s, India" % row[1])
      except ValueError, e:
        self.response.write("%s: FAILED - %s<br>\n" % (row[1], e))
        continue
      name = re.sub(r"'", r"\'", row[1])
      location = search[0].geometry.location
      self.response.write("%s: %s<br>\n" % (name, location))
      logging.info("Updating row %s - %s", row[0], name)
      cursor.execute("UPDATE town SET latitude = %s, longitude = %s WHERE "
                     "name = '%s'" % (location.lat, location.lng, name))
      time.sleep(0.05)


class MainHandler(webapp2.RequestHandler):
  def get(self):
    self.response.write("Hi Steady State!")


class MapHandler(webapp2.RequestHandler):
  def get(self):
    with open("map.html", "r") as f:
      self.response.write(f.read())


class SecretHandler(webapp2.RequestHandler):
  def get(self):
    self.response.write("Welcome to this secret page. Shhh...")


app = webapp2.WSGIApplication([
    ("/", MainHandler),
    ("/db", DBHandler),
    ("/map", MapHandler),
    ("/secret", SecretHandler),
], debug=True)
