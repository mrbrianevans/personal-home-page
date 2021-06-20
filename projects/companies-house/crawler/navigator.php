<div>
    <div class="companyNavSection">
        <h3>Company info</h3>
        <form>
            <label for="companyNumberInput">Search by company number in JSON: </label>
            <input id="companyNumberInput" name="companyNumber" type="text" placeholder="12517505"/>
            <button type="submit" name="action" value="search">Search</button>
        </form>
        <a href="?action=random"><button>Random company</button></a>
        <form>
            <label for="companyNumberInput">Search by company number in database: </label>
            <input id="companyNumberInput" name="companyNumber" type="text" placeholder="12517505"/>
            <button type="submit" name="action" value="database">Search</button>
        </form>
        <a href="?action=randatabase"><button>Random company from database</button></a>
    </div>
    <div class="companyNavSection">
        <h3>API crawler</h3>
        <form>
            <label for="queryInput">Query: </label>
            <input name="query" id="queryInput" placeholder="company name" type="text" autofocus autocomplete="off"/>
            <button type="submit" name="action" value="query">Add new Query to Query</button>
        </form>
        <a href="?action=queue"><button>View query queue</button></a>
        <a href="?action=crawl"><button>Crawl</button></a>
    </div>
    <div class="companyNavSection">
        <h3>Full dataset download</h3>
        <a href="?action=csv"><button>Scan gov CSV to JSON</button></a>
        <a href="?action=sql"><button>Load SQL database from CSV</button></a>

    </div>
    <div class="companyNavSection">
        <h3>Organise records</h3>
        <a href="?action=unsorted"><button>View sort queue</button></a>
        <a href="?action=sort"><button>Sort files</button></a>
        <a href="?action=secondary"><button>Secondary sort files</button></a>
        <a href="?action=files"><button>Display list of files</button></a>
        <a href="?action=stored"><button>Count stored companies</button></a>
        <a href="?action=keys"><button>Fix JSON keys</button></a>
        <form class="inlineForm"><input type="text" placeholder="12" name="category" id="categoryKeyFixInput"/>
            <button name="action" type="submit" value="fix">Fix all keys</button>
        </form>
        <a href="?action=broken"><button>Find broken files</button></a>
        <a href="?action=reset"><button>Send all to uncategorised (reset) ((not recommended))</button></a>
    </div>


    <div class="companyNavSection">
        <h3>Accounts filings</h3>
        <a href="?action=employees"><button>Get employees</button></a>
        <a href="?action=assets"><button>Get net assets</button></a>
    </div>

</div>