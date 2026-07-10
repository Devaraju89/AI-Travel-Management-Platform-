@if(!request()->has('widget'))
@extends('layouts.app')
@endif
@section('title','AI Travel Assistant')

@if(request()->has('widget'))
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelMate AI</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0d9488; --primary-hover: #0f766e; --bg-body: #f8fafc; --surface: #ffffff; --text-main: #0f172a; --text-muted: #64748b; --border: #e2e8f0; --shadow-sm: 0 1px 2px 0 rgba(0,0,0,0.05); }
        * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { margin: 0; padding: 0; background: #fff; overflow: hidden; }
        .chat-container { height: 100vh; }
    </style>
</head>
<body>
@endif

@section('content')
<style>
@if(!request()->has('widget'))
.chat-container { height: calc(100vh - 70px); }
@endif
.chat-container { display: flex; flex-direction: column; background: var(--bg-body); width: 100%; }
.chat-header { background: #fff; padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); box-shadow: var(--shadow-sm); z-index: 10; flex-shrink: 0;}
.chat-header-inner { display: flex; align-items: center; gap: 1rem; }
.bot-avatar { width: 42px; height: 42px; border-radius: 50%; background: #e0f2fe; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; border: 2px solid #bae6fd; flex-shrink: 0;}

.chat-body { flex: 1; overflow-y: auto; padding: 1.5rem 1rem; }
.chat-body-inner { display: flex; flex-direction: column; gap: 1rem; }

.msg-wrap { display: flex; width: 100%; }
.msg-wrap.user { justify-content: flex-end; }

.msg-bubble { max-width: 85%; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.5; position: relative; word-wrap: break-word; }
.msg-wrap.bot .msg-bubble { background: #fff; border: 1px solid var(--border); border-radius: 12px 12px 12px 2px; box-shadow: var(--shadow-sm); color: var(--text-main); }
.msg-wrap.user .msg-bubble { background: var(--primary); color: #fff; border-radius: 12px 12px 2px 12px; box-shadow: 0 4px 10px rgba(13, 148, 136, 0.15); }

.msg-time { font-size: 0.7rem; opacity: 0.7; margin-top: 0.3rem; text-align: right; }

.chat-footer { border-top: 1px solid var(--border); padding: 1rem; background: #fff; z-index: 10; flex-shrink: 0;}
.chat-footer-inner { display: flex; gap: 0.5rem; align-items: flex-end; }
.chat-input { flex: 1; background: #f8fafc; border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.9rem; resize: none; max-height: 100px; outline: none; font-family: 'Inter', sans-serif; transition: 0.2s;}
.chat-input:focus { background: #fff; border-color: var(--primary); box-shadow: 0 0 0 2px rgba(13,148,136,0.1); }
.send-btn { background: var(--primary); color: #fff; border: none; padding: 0.75rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: 0.2s; height: 100%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;}
.send-btn:hover { background: var(--primary-hover); transform: translateY(-1px); }

.chat-suggestion { padding: 0.4rem 0.8rem; background: #f0fdfa; color: var(--primary); border: 1px solid #ccfbf1; border-radius: 50px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: 0.2s; text-align: center; }
.chat-suggestion:hover { background: var(--primary); color: #fff; }

.empty-state { text-align: center; padding: 2rem 0.5rem; }
.empty-icon { font-size: 3rem; margin-bottom: 0.75rem; color: var(--primary); }
.empty-title { font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem; }
.empty-desc { color: var(--text-muted); font-size: 0.85rem; margin: 0 auto 1.5rem; }
</style>

<div class="chat-container">
    <div class="chat-header">
        <div class="chat-header-inner">
            <div class="bot-avatar"><i class="fas fa-robot"></i></div>
            <div>
                <h1 style="font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin: 0;">AI Concierge</h1>
                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.1rem;">Online · TravelMate AI</div>
            </div>
        </div>
    </div>

    <div class="chat-body" id="chat-body">
        <div class="chat-body-inner">
            @forelse($history as $msg)
            <div class="msg-wrap {{ $msg->role==='user'?'user':'bot' }}">
                @if($msg->role==='assistant')
                <div class="bot-avatar" style="width: 28px; height: 28px; font-size: 0.8rem; margin-right: 0.5rem; flex-shrink: 0; align-self: flex-end; margin-bottom: 0.25rem;"><i class="fas fa-robot"></i></div>
                @endif
                <div class="msg-bubble">
                    {!! nl2br(preg_replace([
                        '/\!\[(.*?)\]\((.*?)\)/',
                        '/\*\*(.*?)\*\*/'
                    ], [
                        '<img src="$2" alt="$1" style="max-width:100%;height:auto;border-radius:8px;margin:8px 0;border:1px solid #e2e8f0;">',
                        '<strong>$1</strong>'
                    ], e($msg->message))) !!}
                    <div class="msg-time">{{ $msg->created_at->format('h:i A') }}</div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-robot"></i></div>
                <div class="empty-title">Hi! I'm TravelMate AI</div>
                <div class="empty-desc">I can help you build itineraries, find packages, and estimate budgets.</div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach(['🗺️ Top beach destinations', '📦 Budget packages', '🛂 Visa for Japan', '🗓️ Plan a 7-day Bali trip'] as $s)
                    <button onclick="quickSend(this)" class="chat-suggestion">{{ $s }}</button>
                    @endforeach
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="chat-footer">
        <div class="chat-footer-inner">
            <textarea id="main-chat-input" class="chat-input" rows="1" placeholder="Type a message..."
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMainChat()}"
                oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px'"></textarea>
            <button onclick="sendMainChat()" class="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
        <div style="margin: 0.5rem auto 0; font-size: 0.7rem; color: var(--text-muted); text-align: center;">
            <i class="fas fa-bolt" style="color: var(--primary);"></i> Powered by Advanced Travel AI
        </div>
    </div>
</div>

<script>
let session = null;
const body = document.getElementById('chat-body');
const inner = body.firstElementChild;
body.scrollTop = body.scrollHeight;

function quickSend(btn) { 
    document.getElementById('main-chat-input').value = btn.textContent.replace(/^[\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF]/g, '').trim(); 
    sendMainChat(); 
}

async function sendMainChat() {
    const input = document.getElementById('main-chat-input');
    const msg = input.value.trim(); 
    if (!msg) return;

    appendMsg(msg, 'user'); 
    input.value = ''; 
    input.style.height = 'auto';

    const typing = appendMsg('Typing...', 'bot', false, true);
    
    try {
        const res = await fetch('{{ route("chatbot.send") }}', {
            method:'POST', 
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({message: msg, session_id: session})
        });
        const data = await res.json();
        session = data.session_id;
        
        typing.remove();
        appendMsg(data.response.text, 'bot', true);
        
        if (data.response.suggestions?.length) {
            const sug = document.createElement('div');
            sug.style.cssText = 'display:flex;flex-wrap:wrap;gap:0.5rem;max-width:85%;margin-top:0.5rem;margin-left:40px;';
            data.response.suggestions.forEach(s => {
                const b = document.createElement('button'); 
                b.className = 'chat-suggestion'; 
                b.style.fontSize = '0.75rem';
                b.textContent = s;
                b.onclick = () => quickSend(b); 
                sug.appendChild(b);
            });
            inner.appendChild(sug);
            body.scrollTop = body.scrollHeight;
        }
    } catch(e) { 
        typing.remove();
        appendMsg('Sorry, I encountered an error. Please try again.', 'bot');
    }
}

function appendMsg(text, role, markdown=false, isTyping=false) {
    const wrap = document.createElement('div');
    wrap.className = 'msg-wrap ' + (role==='user'?'user':'bot');
    
    if(role==='bot') {
        const av = document.createElement('div');
        av.className = 'bot-avatar';
        av.style.cssText = 'width: 28px; height: 28px; font-size: 0.8rem; margin-right: 0.5rem; flex-shrink: 0; align-self: flex-end; margin-bottom: 0.25rem;';
        av.innerHTML = '<i class="fas fa-robot"></i>';
        wrap.appendChild(av);
    }
    
    const bubble = document.createElement('div');
    bubble.className = 'msg-bubble';
    if(isTyping) {
        bubble.style.color = 'var(--text-muted)';
        bubble.style.fontStyle = 'italic';
        bubble.innerHTML = '<i class="fas fa-circle-notch fa-spin" style="margin-right:0.5rem;"></i>' + text;
    } else {
        bubble.innerHTML = markdown ? text.replace(/\!\[(.*?)\]\((.*?)\)/g,'<img src="$2" alt="$1" style="max-width:100%;height:auto;border-radius:8px;margin:8px 0;border:1px solid #e2e8f0;">').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>').replace(/\n/g,'<br>') : text;
    }
    
    const time = document.createElement('div');
    time.className = 'msg-time';
    const now = new Date();
    time.textContent = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    if(!isTyping) bubble.appendChild(time);
    
    wrap.appendChild(bubble); 
    inner.appendChild(wrap);
    body.scrollTop = body.scrollHeight;
    
    return wrap;
}
</script>
@endsection

@if(request()->has('widget'))
    @yield('content')
</body>
</html>
@endif
