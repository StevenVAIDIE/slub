Feature: Improve the feedback delay between the squad and the continuous integration (CI) status
  In order to accelerate the feedback loop between the squad and the CI status of PR
  As a squad
  We want to be notified of the pull request CI status updates

  @nominal
  Scenario: Notify the squad when the CI is green for a pull request
    Given a pull request in review
    When the CI is green for the pull request
    Then the PR should be green
    And the squad should be notified that the ci is green for the pull request

  @nominal
  Scenario: Notify the squad when the CI is red for a pull request
    Given a pull request in review
    When the CI is red for the pull request
    Then the PR should be red
    And the squad should be notified that the ci is red for the pull request

  @secondary
  Scenario: It does not notify CI status changes for unsupported repositories
    When the CI status changes for a PR belonging to an unsupported repository
    Then the squad should not be not notified
