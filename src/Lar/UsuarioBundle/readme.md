Usuario Bundle
==============


security.yml

    jms_security_extra:
        secure_all_services: false
        expressions: true

    security:
        firewalls:
            frontend_pr:
                pattern: ^/*
                anonymous: ~
                provider: usuarios
                form_login:
                    use_referer: true
                    login_path: /login
                    check_path: /login_check
                    #default_target_path: home_page
                    always_use_default_target_path: true
    
                logout:
                    path: /logout
    

        access_control:
            - { path: ^/login,          roles: IS_AUTHENTICATED_ANONYMOUSLY }
    
    
            - { path: ^/larparametro/*, roles: ROLE_ADMIN }
            - { path: ^/codigobarra/*,  roles: ROLE_ADMIN }
            - { path: ^/usuario/*,      roles: ROLE_ADMIN }
            - { path: ^/*,              roles: ROLE_USUARIO }
    




        providers:
    #        in_memory:
    #            memory:
    #                users:
    #                    user:  { password: userpass, roles: [ 'ROLE_USER' ] }
    #                    admin: { password: adminpass, roles: [ 'ROLE_ADMIN' ] }
    #        all_provider:
    #            chain:
    #                providers: ["usuarios", "clientes"]
            usuarios:
                entity: { class: Lar\UsuarioBundle\Entity\Usuario,  property:    email }
    

    
    #        clientes:
    #            entity: { class: Stock\StockBundle\Entity\Cliente,    property:    usuario }
    
        encoders:
            Symfony\Component\Security\Core\User\User: plaintext
            Lar\UsuarioBundle\Entity\Usuario: { algorithm: sha512, iterations: 10 }
    
        role_hierarchy:
            ROLE_ADMIN:       [ROLE_USUARIO, ROLE_ADMIN]
            ROLE_USUARIO:     [ROLE_USUARIO]
    `
    
 