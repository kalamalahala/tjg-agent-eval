# Agent Evaluation Notes

### Leadership Access

On the /login/ page, run a shortcode to display buttons for Junior, Senior, AO, and QM roles.

When the logged in user clicks on this **Leadership Access** button, they are taken to a page / URI that displays the following:

1. Reports
    - Pending Business Tracker
    - WAR Report link (upcoming)
    - MROs for their agents?
2. List of Agents - displayed how?
    - grid view?
    - list view?
    - **additional hierarchy** or no?
        - likely not at this time
3. When an individual agent is selected, the user will be prompted to either **View Reports** or **Create Report**.
    - specific to the upcoming "Presentation Progress" reporting forms to be created
4. **View Reports**
    - Show a list of form submissions for the selected Agent.
    - include a **Create Report** button in this area to create for the selected form and agent
5. **Create Report**
    - How many forms to use for this?
        - A multi-page form would allow the entire curriculum to be included in one location and progress could be measured by checkpoints inside the form
        - Individual forms for each quarter of the cirriculum could be listed and displayed easier on the leadership reports panel. 
    - When **Create Report** is selected, user is prompted to select which form will be used for evaluating the Selected Agent (provided by parameter when clicking the Agent back in the Leadership Access section)
    - User will be able to enter any long form comments on all questions and then be asked at the end "pass/fail" or another form of that question.
        - On form submission, look into the idea of updating / creating meta keys via gravity forms action hooks, then I can use have "section_N_passed" meta tags changed.
    - A list of submitted evaluations will be visible on a [gravity view layout](gravity_view_shortcodes.php)

### Agent Level Viewing

This encompasses both a member of leadership wanting to view the current status of an agent's training, as well as being able to display to the logged in user what the status of their own training is.

A shortcode with an attribute to include a couple layouts could be useful here, as there are a few places that this information would need to be displayed.

1. A short text blurb or other small element (icons?) next to the relevant "Create Report" or "View Report" area for the selected agent that will display a calculation or status based on the property selected.
2. Possibly located inside of an Agent Profile page
    - this Agent Profile page would need to be user restricted to the logged in user
    - either a seperate page for supervisors could be created, or manually coded permissions for their related supervisor?

