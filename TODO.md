# The todo list
* Assets pages
    * Display portfolios containing those assets
    * Add default currency for valuation
    * Handle double conversion to display all amounts in same currency

* Movements
    * Additional fields: description of the movement
    * Add possibilities to search / filter on all movements

* Portfolios
    * Identify account as "Regular deposits" (will be displayed differently)
    * Display additional data (full text, tax notes, ...)
    * Add default currency

* Graphs
    * Evolution graph
        * Purpose: displays the value in basis 100 (as %) at the beginning.
        * Status: DONE
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