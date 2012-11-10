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

from google.appengine.api import rdbms
import MySQLdb
import webapp2


class DBHandler(webapp2.RequestHandler):
  def get(self):
    conn = MySQLdb.connect(host="mysql.anjoola.com", user="anjoola",
                           passwd="pokemon", db="ashaforedu")
    cursor = conn.cursor()
    cursor.execute("""
SELECT project.id AS project_id, project.name AS project, project_desc, organization, org_desc, district.name AS district, state.name AS state, country.name AS country FROM project JOIN district JOIN state JOIN country ON (project.district_id = district.id AND project.state_id = state.id AND project.country_id = country.id) WHERE project.status_id = 1 ORDER BY project_id""")
    rows = cursor.fetchall()
    conn.close()
    for row in rows:
      self.response.write("<b>Project #%d</b>: %s<br>\n" % row[:2])


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
