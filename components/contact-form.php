<div class="form-container">
    <form id="messageForm">
        <div class="input-container-front">
            <label for="lastname">Nom</label>
            <input class="input-front" type="text" id="lastname" placeholder="Votre nom" required>
        </div>
        <div class="input-container-front">
            <label for="firstname">Prénom</label>
            <input class="input-front" type="text" id="firstname" placeholder="Votre prénom" required>
        </div>
        <div class="input-container-front">
            <label for="email">Email</label>
            <input class="input-front" type="email" id="email" placeholder="Votre email" required>
        </div>
        <div class="input-container-front">
            <label for="phone">Téléphone</label>
            <input class="input-front" type="text" id="phone" placeholder="Votre téléphone" required>
        </div>
        <div class="input-container-front span-4">
            <label for="message">Subject</label>
            <input class="input-front" type="text" id="subject" placeholder="Le sujet" required>
        </div>
        <label for="message">Message</label>
        <textarea class="input-front" id="message" placeholder="Votre message" rows="5" required></textarea>

        <button type="submit" class="btn">envoyer</button>
    </form>
</div>