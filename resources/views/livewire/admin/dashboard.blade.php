<div>
    <div class="statistics">
        <h2>Statistics</h2>
        <div class="stat-card">
            <h3>Total Content</h3>
            <p>{{ $totalContent }}</p>
        </div>
        <div class="stat-card">
            <h3>Recent Messages</h3>
            <p>{{ $recentMessagesCount }}</p>
        </div>
        @if($lastContentUpdate)
        <div class="stat-card">
            <h3>Last Content Update</h3>
            <p>{{ $lastContentUpdate }}</p>
        </div>
        @endif
    </div>

    @if($recentMessages->isNotEmpty())
    <div class="recent-messages">
        <h2>Recent Contact Messages</h2>
        <ul>
            @foreach($recentMessages as $message)
            <li>
                <strong>{{ $message->name }}</strong> - {{ $message->subject ?? 'No subject' }}
                <br>
                <small>{{ $message->created_at->diffForHumans() }}</small>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>