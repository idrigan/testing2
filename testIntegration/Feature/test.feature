Feature: Create post
  create an post to post to the blog
  blog users
  A post posted on the blog

  Scenario: Post to the blog
    Given create user with "idrigan@gmail.com" and "A123456"
    And with a title "Titulo del post"
    And with a body "Cuerpo del Post"
    When executing the CreatePostUseCase class
    Then A post published in the blog
