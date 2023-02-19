Cypress.Commands.add('login', (email, password) => {

  cy.visit('/homeowner/dashboard');
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
  cy.get('.btn-add-property').click() 
  cy.fixture('property').as('propertyJson').then((property) => {
  cy.get('input[name=title]').type(property.title)
  cy.get('input[name=price]').type(property.price)
  cy.get('input[name=type]').click({force: true}).select(property.type)
    // cy.get('input[name=rooms]').type(property.rooms)
  })
});



it('Adding a property', function () {
  let email = 'owner@test.com';
  let password = 'owner'

  cy.login(email, password)
  
});