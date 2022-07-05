<div>
    <div class="companyNavSection">
        <h3>Company info</h3>

        <a href="?action=random"><button>Random company from database (7s)</button></a>
        <a href="?action=sample"><button>Sample company from database (<0.1s)</button></a>
        <form>
            <label for="companyNumberInput">Search by company number: </label>
            <datalist id="predictionsDatalist">

            </datalist>
            <input id="companyNumberInput" name="number" type="text" placeholder="12517505" onkeyup="predictCompanyNumber()" list="predictionsDatalist"/>
            <button type="submit" name="action" value="search">Search</button>
        </form>

        <form>
            <label for="personSearchTermInput">Search by person with significant control (name and year of birth) </label>
            <input id="personSearchTermInput" name="name" type="text" placeholder="George Orwell 1984"/>
            <button type="submit" name="action" value="person-search">Search</button>
        </form>

        <form method="POST">
            <label for="company-numbers-psc-details-textarea">Convert company numbers to PSC details: </label><br>
            <textarea name="company-numbers" id="company-numbers-psc-details-textarea" style="width: 50%" rows="10"></textarea><br>
            <button type="submit" name="action" value="bulk-psc-details">Convert</button>
        </form>
    </div>

    <div class="companyNavSection">
        <h3>Update database</h3>
        <a href="?action=sql"><button>Load SQL database from CSV</button></a>
        <a href="?action=accounts"><button>Get employees and net assets from accounts filings</button></a>
        <a href="?action=xml"><button>Account filings XML processing</button></a>
        <a href="?action=interpret"><button>Interpret financials context</button></a>
        <a href="?action=persons"><button>Scan PSC file</button></a>
        <a href="?action=delete-financials"><button>Delete Unnecessary Financials</button></a>
        <a href="?action=delete-persons"><button>Delete Ceased persons</button></a>

    </div>
    <div class="companyNavSection">
        <h3>View database details</h3>
        <a href="?action=count"><button>Count companies in database</button></a>
        <a href="?action=count-accounts"><button>Count companies with accounts</button></a>
        <a href="dataProductDownload/accounts"><button>View accounts</button></a>
        <a href="?action=labels"><button>Get list of financial labels</button></a>
    </div>

    <div class="companyNavSection">
        <h3>Query database</h3>
        <form>
            <label for="companyLimitInput">Number of companies: </label>
            <input id="companyLimitInput" name="limit" placeholder="100"/>
            <label for="sortByInput">Sort by </label>
            <datalist id="sortByOptions">
                <option value="employees">Employees</option>
                <option value="currentNetAssets">Net assets</option>
                <option value="previousNetAssets">Previous years net assets</option>
            </datalist>
            <input list="sortByOptions" id="sortByInput" name="sort" placeholder="employees"/>
            <button type="submit" name="action" value="filter">Get companies</button>
        </form>

        <form>
            <h4>Filter by financial <sub>Does not include all companies</sub></h4>

            <label for="financialLabelInput">Financial: </label>
            <input id="financialLabelInput" placeholder="AverageNumberEmployeesDuringPeriod" list="financial-labels-list" name="label"/>
            <br>
            <label for="sortOrderSelect">Order: </label>
            <select name="order" id="sortOrderSelect">
                <option value="ASC">Ascending</option>
                <option value="DESC">Descending</option>
            </select>
            <br>
            Range
            <label for="minimumFinancialValue"> From: </label>
            <input id="minimumFinancialValue" placeholder="1000" name="min"/>
            <label for="maxFinancialValue"> to: </label>
            <input id="maxFinancialValue" placeholder="2000" name="max"/>
            <button type="submit" name="action" value="screen">Screen companies</button>
        </form>

        <form>
            <h4>Filter by SIC code</h4>
            <div>
                <label>Include SICs <select name="sic" id="sic"></select></label>
                <script src="asynchronousOptions.js" onload="loadSicOptions()"></script>
            </div>
            <button type="submit" name="action" value="screen-sic">Get companies</button>
        </form>

        <form>
            <h4>Filter by PSC age</h4>
            <div>
                <label>Older than <input name="lower-age-bound" value="0"></label>
            </div>
            <div>
                <label>Younger than <input name="upper-age-bound" value="100"></label>
            </div>
            <button type="submit" name="action" value="screen-psc-age">Get companies</button>
        </form>
    </div>

</div>