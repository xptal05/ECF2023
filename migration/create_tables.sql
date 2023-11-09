CREATE TABLE
    user_roles (
        id_role int NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        rights varchar(255),
        PRIMARY KEY (id_role)
    );

CREATE TABLE
    users (
        id_user int NOT NULL AUTO_INCREMENT,
        last_name varchar(255),
        first_name varchar(255),
        email varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        active_since datetime,
        role int NOT NULL,
        status int NOT NULL,
        PRIMARY KEY (id_user),
        FOREIGN KEY (role) REFERENCES user_roles(id_role),
        FOREIGN KEY (status) REFERENCES statuses(id_status)
    );

CREATE TABLE
    statuses (
        id_status INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        description VARCHAR(255),
        PRIMARY KEY (id_status)
    );

CREATE TABLE
    messages (
        id_message INT NOT NULL AUTO_INCREMENT,
        client_first_name VARCHAR(255) NOT NULL,
        client_last_name VARCHAR(255) NOT NULL,
        client_email VARCHAR(255),
        client_phone VARCHAR(255),
        subject TEXT(65535) NOT NULL,
        message TEXT(65535) NOT NULL,
        created DATETIME NOT NULL,
        modified DATETIME,
        modified_by INT,
        status INT NOT NULL,
        PRIMARY KEY (id_message),
        FOREIGN KEY (modified_by) REFERENCES users(id_user),
        FOREIGN KEY (status) REFERENCES statuses(id_status)
    );

CREATE TABLE
    feedbacks (
        id_feedback int NOT NULL AUTO_INCREMENT,
        client_name VARCHAR(255) NOT NULL,
        rating INT NOT NULL,
        comment TEXT(65535),
        created DATETIME NOT NULL,
        modified DATETIME,
        modified_by INT,
        status INT NOT NULL,
        PRIMARY KEY (id_feedback),
        FOREIGN KEY (modified_by) REFERENCES users(id_user),
        FOREIGN KEY (status) REFERENCES statuses(id_status)
    );

CREATE TABLE
    brands (
        id_brand int NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        PRIMARY KEY(id_brand)
    );

CREATE TABLE
    models (
        id_model int NOT NULL AUTO_INCREMENT,
        brand int NOT NULL,
        name VARCHAR(255) NOT NULL,
        PRIMARY KEY (id_model),
        FOREIGN KEY (brand) REFERENCES brands(id_brand)
    );

CREATE TABLE
    vehicles (
        id_vehicle int NOT NULL AUTO_INCREMENT,
        brand int NOT NULL,
        model int NOT NULL,
        status int NOT NULL,
        year int,
        km int,
        price int,
        conformity VARCHAR(255),
        consumption VARCHAR(255),
        other_equipment TEXT(65535),
        modified DATETIME,
        created DATETIME,
        modified_by int
        PRIMARY KEY (id_vehicle),
        FOREIGN KEY (brand) REFERENCES brands(id_brand),
        FOREIGN KEY (model) REFERENCES models(id_model),
        FOREIGN KEY (status) REFERENCES statuses(id_status),
        FOREIGN KEY (modified_by) REFERENCES users(id_user)
    );

CREATE TABLE
    properties_meta (
        id_meta int NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        value VARCHAR(255),
        PRIMARY KEY (id_meta)
    );

CREATE TABLE
    vehicle_properties(
        id int NOT NULL AUTO_INCREMENT,
        vehicle int NOT NULL,
        property_name varchar(255),
        property varchar(255)	 NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (vehicle) REFERENCES vehicles(id_vehicle),
    );

CREATE TABLE
    image_types (
        id_img_type int NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        PRIMARY KEY (id_img_type)
    );

CREATE TABLE
    info_types (
        id_info_type int NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        PRIMARY KEY (id_page_type)
    );

CREATE TABLE
    web_page_info (
        id_info INT NOT NULL AUTO_INCREMENT,
        type INT NOT NULL,
        text TEXT,
        order INT,
        PRIMARY KEY (id_info),
        FOREIGN KEY (type) REFERENCES info_types(id_info_type)
    );

CREATE TABLE
    images(
        id_img int NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        link VARCHAR(255) NOT NULL,
        type int NOT NULL,
        associated_to_vehicle int,
        associated_to_info int,
        PRIMARY KEY (id_img),
        FOREIGN KEY (type) REFERENCES image_types(id_img_type),
        FOREIGN KEY (associated_to_vehicle) REFERENCES vehicles(id_vehicle),
        FOREIGN KEY (associated_to_info) REFERENCES web_page_info(id_info)
    );