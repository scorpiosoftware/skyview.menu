<div class="flex flex-col items-center justify-center py-8">
    {{-- <h2 class="text-2xl font-bold mb-4">⏳ Countdown Timer</h2> --}}

    <div
        x-data="{
            countdownTime: new Date('{{ $targetDate }}').getTime(),
            timeLeft: '',
            startCountdown() {
                setInterval(() => {
                    const now = new Date().getTime();
                    const distance = this.countdownTime - now;

                    if (distance < 0) {
                        this.timeLeft = 'Time\'s up!';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                    const minutes = Math.floor((distance / (1000 * 60)) % 60);
                    const seconds = Math.floor((distance / 1000) % 60);

                    this.timeLeft = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                }, 1000);
            }
        }"
        x-init="startCountdown"
        class="text-base font-mono bg-white p-1 text-nowrap rounded-lg shadow-md text-red-600"
        x-text="timeLeft"
    >
    </div>
</div>
