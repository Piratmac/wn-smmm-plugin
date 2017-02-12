




###############################################
Use related movements ==> they will be deleted together


Source                     Target                    Examples
Compte ext NP              PF                        Abondement, dividendes, intérêts
Compte ext P               PF                        Versement
PF                         Compte P                  Retrait
PF cash                    Compte NP                 Impôts, frais, prélèvement sociaux
PF titres                  Compte NP                 Frais en actions, Arrondi ESPP
PF cash                    PF titres                 Achat
PF titres                  PF cash                   Vente
PF titres                  PF titres                 Arbitrage

Transferts inter-comptes? (compliqué :()

###############################################







# The todo list
* Assets pages
    * Display portfolios containing those assets
    * Handle split/merge of assets: through "obsolete" tag and relationship between old and new
    * Add default currency for valuation
    * Handle double conversion to display all amounts in same currency

* Movements
    * Additional fields: description of the movement
    * Add possibilities to search / filter on all movements

* Movement types
    * Create new pages to manage movement types
    * Should have title, description
    * Should have source and target data: portfolio (either user choose, or "external"), asset type (cash / stock)
    * To be confirmed: how to determine if it impacts the portfolio balance ? (main idea: if source or target = external)

* Portfolios
    * Identify account as "Regular deposits" (will be displayed differently)
    * Display additional data (full text, tax notes, ...)
    * Add default currency

* Graphs
    * Evolution graph
        * Purpose: displays the value in basis 100 (as %) at the beginning.
        * Status: Already built
    * Valuation graph
        * Purpose: displays the value in a given currency
        * Status: Not started
        * Notes: Should include double conversion (asset -> base currency -> target currency)
        * May need 2 axis to display different values on each
    * Deposits graph
        * Purpose: Secondary graph (or graph on secondary axis) to display the deposits over time
        * Status: Not started
    * Composition graph
        * Purpose: displays the composition of a portfolio at a given date
        * Status: Not started
    * Composition evolution graph
        * Purpose: display the evolution of the composition of a portfolio
        * Status: Not started
        * Notes: will use stacked line charts (with ability to reorder elements) or line charts (for easy comparison)
        * Tooltips: % or total + value in base currency
    * Assets graph
        * Line chart to display the value of the asset
        * Bar chart to show when it was bought / sold
    * Save / Restore:
        * Graph parameters should be saved as parameters / quicklinks for the users
        * This should include the ability to set relative date values
        * Ability to include new assets or not (= always include portfolio composition or not)
    * Parameters:
        * Allow to change the basis over time when there are entry / exit movements
        * Allow to display the real value or the value in percents
        * Display special dots when there are movements

* Cleanup
    * Switch to back-end management and pages