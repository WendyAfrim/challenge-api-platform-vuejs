Cypress.Commands.add('login', (email, password) => {

  cy.visit('/login');
  cy.get('input[name=email]').type(email)
  cy.get('input[name=password]').type(`${password}{enter}`)
  cy.intercept('/homeowner/dashboard')
  cy.on('uncaught:exception', (e) => {
    return false;
  })
  cy.intercept('GET', '/users/*', {
    statusCode: 200
  })
  cy.get('h1').should('contain', 'Tous mes biens')
});



it('Login page for owner', function () {
  let email = 'owner@test.com';
  let password = 'owner'

  cy.login(email, password)
  
});