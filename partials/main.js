document.addEventListener('DOMContentLoaded', () => {
    const toggleFormButton = document.getElementById("toggleFormButton");
    const confirmPasswordContainer = document.getElementById("confirmPasswordContainer");
    const loginForm = document.getElementById("loginForm");
    const signupForm = document.getElementById("signupForm");
    const loginErrorMessage = document.getElementById('loginError');
    const signupErrorMessage = document.getElementById('signupErrorMessage');
    const errorMessageInput = document.getElementById('errorMessage');
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.edit-comment').forEach(button => {
            button.addEventListener('click', () => editComment(button.dataset.commentId));
        });
    
        document.querySelectorAll('.delete-comment').forEach(button => {
            button.addEventListener('click', () => deleteComment(button.dataset.commentId)); 
        });
    });

    // Počiatočný status : Login forma je zobrazená, signup forma je skrytá
    signupForm.style.display = "none";
    loginForm.style.display = "block"; 

    toggleFormButton.addEventListener("click", () => {
        if (signupForm.style.display === "none") {
            // Show signup form, hide login form
            showForm('signup');
        } else {
            // Show login form, hide signup form
            showForm('login');
        }
    });


    // Erro handling
    if (errorMessageInput && errorMessageInput.value !== '') {
        // Kontrola, či je to  signup error alebo login error
        if (errorMessageInput.parentElement.id === 'signupForm') { 
            showForm('signup');
            showErrorMessage(errorMessageInput.value, 'signupErrorMessage');
        } else if (errorMessageInput.parentElement.id === 'loginForm') {
            showForm('login');
            showErrorMessage(errorMessageInput.value, 'loginError');
        }
    }

    // Zobrazenie formulára
    function showForm(formId) {
        loginForm.style.display = formId === 'login' ? 'block' : 'none';
        signupForm.style.display = formId === 'signup' ? 'block' : 'none';
        confirmPasswordContainer.style.display = formId === 'signup' ? 'block' : 'none';
        toggleFormButton.textContent = formId === 'login' ? "Don't have an account? Sign up!" : 'Already have an account? Log in!';
    }


    // Zobrazenie chybovej správy
    function showErrorMessage(message, elementId) {
        const errorMessageElement = document.getElementById(elementId);
        if (errorMessageElement) { 
            errorMessageElement.textContent = message;
            errorMessageElement.style.display = "block";
        }
    }

    // Získanie edit tlačidiel
    const editButtons = document.querySelectorAll('.edit-comment');

    // Pridanie event listenerov na edit tlačítka
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.commentId;
        });
    });

    // Získanie delete tlačidiel
    const deleteButtons = document.querySelectorAll('.delete-comment');

    // Pridanie event listenerov na delete tlačítka
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.commentId;
            if (confirm('Are you sure you want to delete this comment?')) {
            }
        });

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const commentId = button.dataset.commentId;
                const commentElement = button.closest('.comment'); // Nájdenie rodičovského comment elementu
        
                // Získanie komentáru cez AJAX
                fetch(`/partials/get_comment.php?id=${commentId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Nahradenie formulára obsahom komentára
                        commentElement.innerHTML = `
                            <form class="edit-comment-form" method="POST" action="/partials/edit_comment.php">
                                <input type="hidden" name="comment_id" value="${commentId}">
                                <textarea name="content" required>${data.content}</textarea>
                                <button type="submit">Save Changes</button>
                                <button type="button" class="cancel-edit">Cancel</button>
                            </form>
                        `;
        
                        // Event listener pre cancel tlačítko
                        const cancelButton = commentElement.querySelector('.cancel-edit');
                        cancelButton.addEventListener('click', () => {
                            // Vrátenie späť na pôvodný obsah komentára
                            commentElement.innerHTML = `
                                <p><strong>${data.username}:</strong> ${data.content}</p>
                                <p class="comment-timestamp">${data.created_at}</p>
                                <div class="comment-actions">
                                    <button class="edit-comment" data-comment-id="${commentId}">Edit</button>
                                    <button class="delete-comment" data-comment-id="${commentId}">Delete</button>
                                </div>
                            `;
                        });
                    });
            });
        });



    // Funckionalita delte tlačítka
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const commentId = button.dataset.commentId;
            if (confirm('Are you sure you want to delete this comment?')) {
                // AJAX požiadavka pre delete_comment.php
                fetch('/partials/delete_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `comment_id=${commentId}` 
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Odstránenie comment elementu z DOM
                        button.closest('.comment').remove();
                    } else {
                        alert(data.error); // Zobrazenie chybovej správy
                    }
                });
            }
        });
    });

    // FUnckionalita pridávanie komentárov
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', (event) => {
            event.preventDefault();

            const formData = new FormData(commentForm);

            // Odoslanie AJAX požiadavky do add_comment.php
            fetch(commentForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentsContainer = document.querySelector('.comments-container');
                    
                    fetch(window.location.href) // Reload stránky
                        .then(response => response.text())
                        .then(html => {
                            // Nájdenie nového comments kontajnera v  the znovunačítanom HTML
                            const newCommentsContainer = new DOMParser().parseFromString(html, 'text/html').querySelector('.comments-container');
                            // Náhrada starého za nový
                            commentsContainer.innerHTML = newCommentsContainer.innerHTML;
                        });
                } else {
                    alert(data.error);
                }
            });
        });
    }

    
    const editButtons = document.querySelectorAll('.edit-comment');
    editButtons.forEach(button => {
      button.addEventListener('click', (event) => {
        const commentId = button.dataset.commentId;
        editComment(commentId, event); // Odoslanie event objektu
      });
    });
      

// Funkcionalita delete tlačítka
    const deleteButtons = document.querySelectorAll('.delete-comment');
    deleteButtons.forEach(button => {
    button.addEventListener('click', (event) => {
        const commentId = button.dataset.commentId;
        const newsId = button.closest('.news-item-with-comments').querySelector('input[name="news_id"]').value; // Získanie news id

        deleteComment(commentId, newsId, event.target); //newsid do deletecommentu
    });
    });


})




});























// funckia na vymazanie komentára
function deleteComment(commentId, event) {
    if (confirm('Are you sure you want to delete this comment?')) {
        const formData = new FormData(); // vytvorenie formulára
        formData.append('comment_id', commentId);
        formData.append('confirm_delete', 'no'); // Pridanie confirm_delete pre 1. volanie
        fetch('/partials/delete_comment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData) // Použitie URLSearchParams na správne zakódovanie dát
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            if (data.confirmation) {
                // Ak je potrebné potvrdenie, spýtame sa používateľa a ak potvrdí, znova odošleme požiadavku s confirm_delete=yes
                if (confirm(data.confirmation)) {
                    return fetch('/partials/delete_comment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `comment_id=${commentId}&confirm_delete=yes`
                    }).then(response => response.json());
                } else {
                    // Ak používateľ zruší zmazanie, vyhodíme výnimku
                    throw new Error('Deletion cancelled.'); 
                }
            } else {
                // Ak potvrdenie nie je potrebné, vráti dáta
                return data; 
            }
        })
        .then(data => {
            if (data.success) {
                alert('Komentár odstránený!');
                location.reload();
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message === 'Deletion cancelled.' ? error.message : 'An error occurred while deleting the comment. Please try again.');
        });
    }
}

  
  
  








// Funkcia na úpravu komentára
async function editComment(commentId) {
    const commentElement = document.getElementById(`comment-${commentId}`);

    try {
        const response = await fetch(`/partials/get_comment.php?id=${commentId}`);
        const originalData = await response.json(); 

        if (originalData.error) {
            alert(originalData.error);
            return;
        }

        // Vytvorenie nového elementu pre formulár, aby sme mohli neskôr nahradiť pôvodný komentár
        const formContainer = document.createElement('div');
        formContainer.innerHTML = `
            <form class="edit-comment-form" method="POST" action="/partials/edit_comment.php">
                <input type="hidden" name="comment_id" value="${commentId}">
                <textarea name="content" required>${originalData.content}</textarea>
                <button type="submit">Save Changes</button>
                <button type="button" class="cancel-edit">Cancel</button>
            </form>
        `;

        // Nahradenie pôvodného komentára formulárom
        commentElement.replaceWith(formContainer);
        const form = formContainer.querySelector('.edit-comment-form');
        const cancelButton = formContainer.querySelector('.cancel-edit');
        
        cancelButton.addEventListener('click', () => cancelEditComment(commentId, originalData, formContainer)); // Pridanie eventu

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            submitEditComment(commentId, formData, formContainer, originalData, event); 
        });

    } catch (error) {
        console.error('Chyba pri načítaní komentára:', error);
        alert('Vyskytla sa chyba pri načítaní komentára. Prosím skúste to znova.');
    }
}

// Funkcia na submit editu komentára
async function submitEditComment(commentId, formData, formContainer, originalData, event) {
    try {
        const response = await fetch(formContainer.querySelector('.edit-comment-form').action, {
            method: 'POST',
            body: formData
        });
        if (!response.ok) {
            throw new Error(`Chyba servera: ${response.status} - ${response.statusText}`);
        }
  
        const data = await response.json();
        console.log("Odpoveď zo servera:", data); 
  
        if (data.require_confirmation) {
          // Potvrdenie úpravy: Zobrazenie dialógového okna s potvrdením
          if (confirm('Are you sure you want to edit this comment?')) {
            formData.append('confirm_edit', true);
            return submitEditComment(commentId, formData, formContainer, originalData, event); // Rekurzívne volanie
          } else {
            location.reload();
          }
        } else if (data.success) {
            location. reload()
          // Úspešná úprava komentára: Aktualizácia v DOM a pridanie event listenerov
          // Vytvorenie nového elementu pre komentár
          const newCommentElement = document.createElement('div');
          newCommentElement.id = `comment-${commentId}`; // Nastavenie správneho ID
          newCommentElement.classList.add('comment'); // Pridanie triedy "comment"
          newCommentElement.innerHTML = `
            <p><strong>${data.comment.username}:</strong> ${data.comment.content}</p>
            <p class="comment-timestamp">${data.comment.created_at}</p>
            <div class="comment-actions">
              <button class="edit-comment" data-comment-id="${commentId}">Edit</button>
              <button class="delete-comment" data-comment-id="${commentId}">Delete</button>
            </div>
          `;
  
          // Nahradenie formulára novým komentárom
          formContainer.replaceWith(newCommentElement);
          
          // Pridanie event listenerov pre nové tlačidlá
            const newEditButton = newCommentElement.querySelector('.edit-comment');
            newEditButton.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                editComment(commentId);
                
            });

            const newDeleteButton = newCommentElement.querySelector('.delete-comment');
            newDeleteButton.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                deleteComment(commentId); 
            });
        } else {
          // V prípade akejkoľvek inej odpovede (aj s kľúčom 'error'), ktorá neznamená úspech, zobrazíme chybu
          throw new Error(data.error || 'Neznáma chyba pri úprave komentára.'); 
        }
      } catch (error) {
        console.error('Chyba pri úprave komentára:', error);
        alert('Vyskytla sa chyba pri úprave komentára. Prosím skúste to znova.');
      }
}


// Funckia na zrušenie editu komentára
function cancelEditComment(commentId, originalData, formContainer) {
    location.reload()
  const newCommentElement = document.createElement('div');
  newCommentElement.id = `comment-${commentId}`;
  newCommentElement.classList.add('comment');
  newCommentElement.innerHTML = `
    <p><strong>${originalData.username}:</strong> ${originalData.content}</p>
    <p class="comment-timestamp">${originalData.created_at}</p>
    <div class="comment-actions">
        <button class="edit-comment" data-comment-id="${commentId}">Edit</button>
        <button class="delete-comment" data-comment-id="${commentId}">Delete</button>
    </div>
  `;
  
  formContainer.replaceWith(newCommentElement);

  const newEditButton = newCommentElement.querySelector('.edit-comment');
            newEditButton.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                editComment(commentId);
            });

            const newDeleteButton = newCommentElement.querySelector('.delete-comment');
            newDeleteButton.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                deleteComment(commentId); 
            });
}
