HDI-Report
==========

A more extensive statistical analysis of sales figures. Provides the ability to graph the sales figures as a bar or line chart. By setting filters further customizable.
Originally registered: 2011-12-08 by Rafael Dabrowski on former OXIDforge.


OXID eShop Version:

- **OXID eShop 6.0 - 6.2**

- OXID eShop 4 Download: [Link](../../tree/b-0.9.x)

Installation:

    composer config repo.OxidCommunity/hdiReport git https://github.com/OXIDprojects/HDI-Report/

    composer require oxid-community/hdireport

Changelog:

	v0.8:   Initial;
	v0.9:   History function; Save Favorit View; minor bug fixes.
	v0.9.1: Bug Fixes
	v0.9.2: New Filter for max and minimum Values; New limit filter; Possible output as table; Layout improvement; New sortcriteria 
	v0.9.3: Added metadata.php to use the extension in OXID > 4.5 
	v0.9.4: Changed structure for use in OXID > 4.7
	v0.9.5: Moved all files to module-folder for use in OXID > 4.7
	v0.9.6: Compatibility to 4.9
    v2.0.x: OXID 6 Module by eComStyle.de
    v2.0.3: Compatibility to 6.2
    v2.0.4: Backwards Compatibility to 6.1 and 6.0
    v2.0.5: date filter "all" fixed; "save" label fixed; version removed from composer.json
	
Licensing: 

	HEINER DIRECT GmbH & Co KG
	Author: Rafael Dabrowski

	Copyright 2011 HEINER DIRECT GmbH & Co KG

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.