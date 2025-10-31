<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cre-ate New User // DATA ERROR</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Unsettling Patterned Background */
        body {
            /* Aggressive, clashing lime and dark purple-black stripes */
            background-color: #0d0d0d;
            background-image: repeating-linear-gradient(45deg, #1a1a1a 0, #1a1a1a 10px, #bfff00 10px, #bfff00 11px);
            min-height: 100vh;
            overflow-x: hidden; /* Hide any accidental horizontal overflow */
            font-family: 'Inter', monospace;
        }

        /* Input field focus/error states to be jarring */
        .jarring-input:focus {
            border-color: #ff00ff !important; /* Neon magenta on focus */
            box-shadow: 0 0 0 3px rgba(255, 0, 255, 0.5) !important;
            outline: none;
        }

        /* Uncomfortable Button Hover */
        .jarring-button:hover {
            background-color: #ff3366 !important; /* Fleshy pink */
            box-shadow: 0 0 30px #ff3366, 0 0 5px #00ffff !important; /* Mixed shadow colors */
            transform: scale(1.02) rotate(-1deg);
        }
    </style>
</head>
<body class="p-8">

    <div class="max-w-4xl mx-auto mt-12 mb-20">
        
        <!-- Surreal Container: Slightly rotated, heavy shadow, warped colors -->
        <div class="
            bg-purple-900/80 p-8 md:p-12 
            rounded-3xl border-4 border-lime-500 
            shadow-[20px_20px_0_rgba(192,38,211,0.7)] 
            transform rotate-1 
            hover:rotate-0 transition-all duration-300
            backdrop-blur-sm
        ">
            <!-- Header: Mismatched and anxious typography -->
            <h1 class="text-3xl md:text-5xl font-extrabold text-lime-400 tracking-widest uppercase mb-2">
                CREATE<span class="text-pink-400 text-6xl">::</span>USER
            </h1>
            <p class="text-pink-200 text-lg mb-8 font-light italic border-b border-pink-400 pb-4">
                Enter the details. The system is watching. Do not deviate.
            </p>

            <!-- Success/Error Messages - Disturbing style -->
            @if (session('success'))
                <div class="bg-lime-900 border-4 border-lime-400 text-lime-100 p-4 mb-6 rounded-lg shadow-xl font-bold transform skew-y-1">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-900 border-4 border-red-500 text-yellow-300 p-4 mb-6 rounded-lg shadow-xl font-bold transform skew-y-1">
                    <strong class="text-xl block mb-2">INPUT ERROR. MALFORMED DATA.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-sm list-none">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('super.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                @csrf

                <!-- Name Field -->
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium uppercase text-pink-400 tracking-widest">
                        Entity Name <span class="text-lime-400 text-xs">(Identity is mandatory)</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                        class="jarring-input mt-1 block w-full bg-gray-900/60 border-2 border-lime-600 rounded-lg p-3 
                                text-lime-300 shadow-inner placeholder-gray-500 text-lg h-14 
                                @error('name') border-red-500 bg-red-900/50 @enderror">
                </div>

                <!-- Email Field -->
                <div class="col-span-1">
                    <label for="email" class="block text-sm font-medium uppercase text-pink-400 tracking-widest">
                        Contact ECHO <span class="text-lime-400 text-xs">(Unique signal required)</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                        class="jarring-input mt-1 block w-full bg-gray-900/60 border-2 border-lime-600 rounded-lg p-3 
                                text-lime-300 shadow-inner placeholder-gray-500 text-lg h-10 
                                @error('email') border-red-500 bg-red-900/50 @enderror">
                </div>

                <!-- Password Field -->
                <div class="col-span-1">
                    <label for="password" class="block text-sm font-medium uppercase text-pink-400 tracking-widest">
                        Access KEY (Min. 8 bits)
                    </label>
                    <input type="password" name="password" id="password" required 
                        class="jarring-input mt-1 block w-full bg-gray-900/60 border-2 border-lime-600 rounded-lg p-3 
                                text-lime-300 shadow-inner placeholder-gray-500 text-lg h-14 
                                @error('password') border-red-500 bg-red-900/50 @enderror">
                </div>

                <!-- Password Confirmation Field -->
                <div class="col-span-1">
                    <label for="password_confirmation" class="block text-sm font-medium uppercase text-pink-400 tracking-widest">
                        Confirm KEY (Verify Duplication)
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                        class="jarring-input mt-1 block w-full bg-gray-900/60 border-2 border-lime-600 rounded-lg p-3 
                                text-lime-300 shadow-inner placeholder-gray-500 text-lg h-10
                                @error('password') border-red-500 bg-red-900/50 @enderror">
                </div>

                <!-- Role Field (Misaligned with other fields) -->
                <div class="col-span-1 mt-6 -rotate-1">
                    <label for="role" class="block text-sm font-bold uppercase text-lime-400 tracking-widest mb-1">
                        ASSIGNED ROLE <span class="text-pink-400 text-xs">(Hierarchy is static)</span>
                    </label>
                    <select name="role" id="role" required 
                        class="jarring-input block w-full bg-gray-900/60 border-2 border-pink-500 rounded-xl p-3 text-pink-300 text-xl appearance-none 
                                @error('role') border-red-500 bg-red-900/50 @enderror">
                        <option value="" disabled selected class="text-gray-500">SELECT REALITY LEVEL</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }} class="bg-purple-900 text-lime-300">
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                    <!-- Visual indicator of selection -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-pink-400">
                        <!-- Strange SVG element as a dropdown icon -->
                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <!-- Bidang ID Field (Displaced) -->
                <div class="col-span-1 mb-8">
                    <label for="bidang_id" class="block text-sm font-bold uppercase text-lime-400 tracking-widest mb-1">
                        BIDANG_ID <span class="text-pink-400 text-xs">(Sector Assignment)</span>
                    </label>
                    <select name="bidang_id" id="bidang_id" required 
                        class="jarring-input block w-full bg-gray-900/60 border-2 border-pink-500 rounded-xl p-3 text-pink-300 text-lg h-10 appearance-none 
                                @error('bidang_id') border-red-500 bg-red-900/50 @enderror">
                        <option value="" disabled selected class="text-gray-500">SELECT NULL SPACE</option>
                        <!-- Simulating Blade loop for bidangs -->
                        @foreach ($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }} class="bg-purple-900 text-lime-300">
                                {{ $bidang->nama }} (ID: {{ $bidang->id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Submission Button (Col Span 2 for emphasis) -->
                <div class="col-span-full pt-4">
                    <button type="submit" 
                        class="jarring-button w-full px-6 py-4 
                                bg-lime-700 text-purple-900 text-2xl font-black 
                                rounded-full border-4 border-lime-400 
                                shadow-2xl shadow-lime-500/50
                                transition-all duration-300 ease-in-out
                                transform skew-x-2">
                        INITIATE NEW ENTITY // SUBMIT
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Back Button - Small, isolated, and slightly out of place -->
        <div class="text-center mt-8 transform translate-x-12">
            <a href="{{ route('super.users.index') }}" class="text-xs text-pink-400 hover:text-lime-300 italic underline">
                &lt; BACK TO INDEX (FLEE REALITY)
            </a>
        </div>

    </div>
</body>
</html>
