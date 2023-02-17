Cypress.Commands.add('login', (email, password) => {

  cy.visit('/login');
  cy.get('input[name=email]').type(email)
  cy.get('input[name=password]').type(`${password}{enter}`)
  cy.url().should('include', '/homeowner/dashboard')
  cy.get('h1').should('contain', 'Tous')
});

it('Login page for owner', function () {
  let email = 'owner@test.com';
  let password = 'owner'

  cy.login(email, password)
  
});