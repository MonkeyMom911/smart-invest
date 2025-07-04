@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<style>
    .faq-container {
        background: linear-gradient(135deg, #0f0f23 0%, #1a1a3a 50%, #2d2d5f 100%);
        min-height: 100vh;
        padding: 3rem 1rem;
        position: relative;
        overflow: hidden;
    }

    .faq-title {
        font-size: 3rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 2rem;
        background: linear-gradient(45deg, #64ffda, #bb86fc, #ffd700);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradientShift 3s ease-in-out infinite;
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .faq-grid {
        max-width: 800px;
        margin: 0 auto;
    }

    .faq-item {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 1rem;
        transition: 0.3s ease;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .faq-item h3 {
        font-size: 1.2rem;
        color: #64ffda;
        margin: 0;
    }

    .faq-item p {
        margin-top: 0.5rem;
        color: #d1d5db;
        display: none;
    }

    .faq-item.active p {
        display: block;
    }
</style>

<div class="faq-container">
    <h1 class="faq-title">Pertanyaan yang Sering Diajukan</h1>

    <div class="faq-grid">
        @foreach ([
            ['Apa itu platform ini?', 'Platform ini adalah tempat Anda bisa melakukan investasi digital dengan mudah dan aman.'],
            ['Apakah dana saya aman?', 'Dana Anda disimpan dengan protokol keamanan tinggi dan diawasi oleh tim profesional.'],
            ['Bagaimana saya bisa memulai investasi?', 'Cukup daftar, verifikasi akun, lalu mulai investasi dari Rp 10.000.'],
            ['Apakah ada biaya tambahan?', 'Tidak, semua biaya telah transparan dan dijelaskan di awal transaksi.'],
            ['Bagaimana saya menarik keuntungan saya?', 'Anda bisa menarik saldo ke rekening kapan saja melalui menu penarikan.'],
        ] as $faq)
            <div class="faq-item" onclick="this.classList.toggle('active')">
                <h3>{{ $faq[0] }}</h3>
                <p>{{ $faq[1] }}</p>
            </div>
        @endforeach
    </div>
    {{-- ... kode FAQ yang sudah ada ... --}}
</div>

<div id="chatbot-container">
    <div id="chatbot-button" class="chatbot-btn-open">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
            <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.352 0 9.75-3.62 9.75-8.125s-4.398-8.125-9.75-8.125S2.25 6.229 2.25 10.75c0 1.55.492 3.003 1.342 4.296a6.685 6.685 0 01-1.242 2.378c-.246.33.03.784.425.784h2.001Z" clip-rule="evenodd" />
        </svg>
    </div>

    <div id="chatbot-window" class="chatbot-window-closed">
        <div class="chatbot-header">
            <span>Smart Assistant</span>
            <button id="chatbot-close-btn">&times;</button>
        </div>
        <div id="chatbot-body" class="chatbot-body">
            <div class="chat-message chatbot">Halo! Ada yang bisa saya bantu terkait investasi di Smart-Invest?</div>
        </div>
        <div class="chatbot-footer">
            <input type="text" id="chatbot-input" placeholder="Ketik pertanyaan Anda...">
            <button id="chatbot-send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                  <path d="M3.478 2.404a.75.75 0 00-.926.941l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.404Z" />
                </svg>
            </button>
        </div>
    </div>
</div>

<style>
    /* Chatbot Styling */
    .chatbot-btn-open {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: linear-gradient(45deg, #64ffda, #bb86fc);
        color: #0f0f23;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(100, 255, 218, 0.3);
        z-index: 9998;
        transition: all 0.3s ease;
    }
    .chatbot-btn-open:hover {
        transform: scale(1.1);
    }
    .chatbot-window-closed {
        display: none;
    }
    .chatbot-window-open {
        position: fixed;
        bottom: 7rem;
        right: 2rem;
        width: 350px;
        height: 500px;
        background: rgba(15, 15, 35, 0.98);
        border: 1px solid rgba(100, 255, 218, 0.2);
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        z-index: 9999;
        overflow: hidden;
    }
    .chatbot-header {
        background: rgba(100, 255, 218, 0.1);
        padding: 1rem;
        color: #64ffda;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chatbot-header button {
        background: none;
        border: none;
        color: #64ffda;
        font-size: 1.5rem;
        cursor: pointer;
    }
    .chatbot-body {
        flex-grow: 1;
        padding: 1rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .chat-message {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        max-width: 80%;
        line-height: 1.4;
    }
    .chat-message.user {
        background-color: #64ffda;
        color: #0f0f23;
        align-self: flex-end;
        border-bottom-right-radius: 0;
    }
    .chat-message.chatbot {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        align-self: flex-start;
        border-bottom-left-radius: 0;
    }
    .chatbot-footer {
        display: flex;
        padding: 0.5rem;
        border-top: 1px solid rgba(100, 255, 218, 0.2);
    }
    .chatbot-footer input {
        flex-grow: 1;
        border: none;
        background: transparent;
        padding: 0.75rem;
        color: white;
        outline: none;
    }
    .chatbot-footer button {
        background: none;
        border: none;
        color: #64ffda;
        padding: 0.5rem;
        cursor: pointer;
    }
</style>
</div>

<script>
    // Optional JS if you want one open at a time
    // document.querySelectorAll('.faq-item').forEach(item => {
    //     item.addEventListener('click', () => {
    //         document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
    //         item.classList.add('active');
    //     });
    // });
     document.addEventListener('DOMContentLoaded', function() {
        const chatButton = document.getElementById('chatbot-button');
        const chatWindow = document.getElementById('chatbot-window');
        const closeBtn = document.getElementById('chatbot-close-btn');
        const sendBtn = document.getElementById('chatbot-send-btn');
        const inputField = document.getElementById('chatbot-input');
        const chatBody = document.getElementById('chatbot-body');

        // Toggle chat window
        chatButton.addEventListener('click', () => {
            chatWindow.style.display = 'flex';
            chatWindow.classList.add('chatbot-window-open');
        });

        closeBtn.addEventListener('click', () => {
            chatWindow.classList.remove('chatbot-window-open');
            setTimeout(() => {
                chatWindow.style.display = 'none';
            }, 300); // Wait for animation
        });

        // Send message function
        const sendMessage = async () => {
            const question = inputField.value.trim();
            if (question === '') return;

            // Display user's message
            appendMessage(question, 'user');
            inputField.value = '';

            // Get response from backend
            try {
                const response = await fetch("{{ route('chatbot.handle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ question: question })
                });

                const data = await response.json();
                
                // Display chatbot's response
                setTimeout(() => {
                    appendMessage(data.answer, 'chatbot');
                }, 500); // Simulate typing

            } catch (error) {
                console.error('Error:', error);
                appendMessage('Maaf, terjadi kesalahan. Coba lagi nanti.', 'chatbot');
            }
        };

        // Append message to chat body
        const appendMessage = (text, type) => {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message', type);
            messageDiv.textContent = text;
            chatBody.appendChild(messageDiv);
            chatBody.scrollTop = chatBody.scrollHeight; // Auto-scroll to bottom
        };

        // Event listeners
        sendBtn.addEventListener('click', sendMessage);
        inputField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    });
</script>
@endsection
