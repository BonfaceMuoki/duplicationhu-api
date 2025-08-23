@extends('layouts.master')

@section('title', 'Duplication Hub - Duplicate and Win | ' . config('app.name'))

@section('meta_description', 'Join Duplication Hub and learn how to Duplicate and Win. Get step-by-step guidance for multiple online platforms and build your duplicating team with ease.')

@section('og_type', 'website')

@section('additional_css')
<style>
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .value-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .value-card:hover {
        transform: translateY(-5px);
        border-color: #667eea;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .cta-gradient {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .platform-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .platform-card:hover {
        transform: translateY(-5px);
        border-color: #667eea;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-gradient text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
            🔁 Welcome to the
            <span class="block text-yellow-300">Duplication Hub</span>
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-4xl mx-auto text-blue-100">
            <span class="font-semibold text-yellow-300">Duplicate and Win.</span>
        </p>
        <p class="text-lg md:text-xl mb-8 max-w-4xl mx-auto text-blue-100">
            Are you tired of joining platforms and not knowing what to do next?
        </p>
        <p class="text-lg md:text-xl mb-8 max-w-4xl mx-auto text-blue-100">
            At Duplication Hub, we remove the confusion. We give you a clear, simple path to follow — 
            so you can start earning faster, help others do the same, and build a duplicating team with ease.
        </p>
        <p class="text-lg md:text-xl mb-8 max-w-4xl mx-auto text-blue-100">
            This isn't about hype or hard selling. It's about using a working system to 
            <span class="font-semibold text-yellow-300">Duplicate and Win.</span>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#what-is" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-xl font-bold text-lg hover:bg-yellow-300 transition-all duration-200 transform hover:scale-105 shadow-lg">
                Learn More
            </a>
            <a href="#platforms" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-100 transition-all duration-200 transform hover:scale-105 shadow-lg">
                Choose Your Platform
            </a>
        </div>
    </div>
</section>

<!-- What Is Duplication Hub Section -->
<section id="what-is" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                💡 What Is <span class="gradient-text">Duplication Hub?</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                Duplication Hub is your step-by-step success system for multiple online platforms.
            </p>
        </div>
        
        <div class="text-center mb-12">
            <p class="text-xl text-gray-700 max-w-4xl mx-auto mb-8">
                Instead of struggling to explain how things work, you simply:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-xl">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Choose a platform</h3>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-xl">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Follow the setup guide</h3>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-xl">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Share your own personalized duplication page</h3>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <p class="text-lg text-gray-700 max-w-4xl mx-auto">
                Whether you're promoting KhayaCONNECT, InovoCB, Spark Agro Life, LiveGood, or other income opportunities, 
                the Duplication Hub helps you build with confidence — no experience needed.
            </p>
        </div>
    </div>
</section>

<!-- Why This Works Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                🔥 Why This <span class="gradient-text">Works</span>
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="value-card bg-white p-8 rounded-2xl text-center">
                <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">✅ No explaining</h3>
                <p class="text-gray-600">
                    We provide ready-made pages that explain everything for you.
                </p>
            </div>
            
            <div class="value-card bg-white p-8 rounded-2xl text-center">
                <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">✅ No chasing people</h3>
                <p class="text-gray-600">
                    Just plug in your link, follow the steps, and share — it's that easy.
                </p>
            </div>
            
            <div class="value-card bg-white p-8 rounded-2xl text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">✅ No complicated tools</h3>
                <p class="text-gray-600">
                    Because here, it's not about effort alone — it's about learning to Duplicate and Win.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                🔒 Why You Can <span class="gradient-text">Trust</span> Duplication Hub
            </h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                Your trust is everything — and we don't take it lightly.
            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <p class="text-lg text-gray-700 mb-6">
                At Duplication Hub, we go the extra mile to thoroughly research, test, and validate every opportunity 
                before it's shared on this platform. We understand how many people have been misled or scammed online, 
                and we're here to do the opposite — to protect, guide, and empower you.
            </p>
            <p class="text-lg text-gray-700 mb-6">
                We only feature platforms that we or our close partners have personally used, with real results. 
                Every link, page, and guide you find here is designed to help you move forward with clarity, safety, and confidence.
            </p>
            <p class="text-lg text-gray-700">
                You're not just clicking random links — you're following a system built on integrity, transparency, and your success.
            </p>
        </div>
    </div>
</section>

<!-- Platforms Section -->
<section id="platforms" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                🧩 Choose Your <span class="gradient-text">Platform</span> to Get Started
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                👇 Click on a platform below to access your step-by-step setup guide:
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="platform-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-32 bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center">
                    <span class="text-white text-4xl">🚀</span>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">KhayaCONNECT</h3>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-800">
                        Get Started →
                    </a>
                </div>
            </div>
            
            <div class="platform-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-32 bg-gradient-to-br from-green-400 to-blue-600 flex items-center justify-center">
                    <span class="text-white text-4xl">💸</span>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">InovoCB</h3>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-800">
                        Get Started →
                    </a>
                </div>
            </div>
            
            <div class="platform-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-32 bg-gradient-to-br from-yellow-400 to-green-600 flex items-center justify-center">
                    <span class="text-white text-4xl">🌾</span>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Spark Agro Life</h3>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-800">
                        Get Started →
                    </a>
                </div>
            </div>
            
            <div class="platform-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-32 bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center">
                    <span class="text-white text-4xl">🌿</span>
                </div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">LiveGood</h3>
                    <a href="#" class="text-blue-600 font-semibold hover:text-blue-800">
                        Get Started →
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center mb-12">
            <p class="text-lg text-gray-600 mb-4">💼 Other Platforms Coming Soon</p>
            <p class="text-lg text-gray-700">
                Each page includes:
            </p>
            <ul class="text-lg text-gray-700 mt-4 space-y-2">
                <li>• Registration link</li>
                <li>• Activation instructions</li>
                <li>• WhatsApp support group</li>
                <li>• Request form to get your own duplication page</li>
            </ul>
        </div>
    </div>
</section>

<!-- Who This Is For Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                📣 Who This <span class="gradient-text">Is For</span>
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl">🔰</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Beginners</h3>
                <p class="text-gray-600">who need simple guidance</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl">🤝</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Leaders</h3>
                <p class="text-gray-600">who want duplicating teams</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl">⏱</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Busy people</h3>
                <p class="text-gray-600">who want results without pressure</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl">🔄</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Anyone tired</h3>
                <p class="text-gray-600">of starting over with each platform</p>
            </div>
        </div>
    </div>
</section>

<!-- Get Your Own Page Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                📲 Want Your Own <span class="gradient-text">Duplication Page?</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-4xl mx-auto">
                If you'd like to use this exact system to grow your team, we'll customize it for you — 
                branded with your name and your personal referral link.
            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-white p-8 rounded-2xl shadow-lg mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Here's how to get yours:</h3>
                <ol class="text-lg text-gray-700 space-y-4">
                    <li class="flex items-start space-x-3">
                        <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center flex-shrink-0">1</span>
                        <span>Choose the platform you like</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center flex-shrink-0">2</span>
                        <span>Register and activate your account</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center flex-shrink-0">3</span>
                        <span>Get your unique referral link</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <span class="w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center flex-shrink-0">4</span>
                        <span>Submit your details using the form below</span>
                    </li>
                </ol>
            </div>
            
            <p class="text-lg text-gray-700 text-center mb-8">
                We'll create a personal duplication page for you — ready to share, easy to duplicate.
            </p>
            
            <div class="text-center">
                <a href="#" class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors duration-200">
                    📩 Click here to request your personalized page
                </a>
                <p class="text-sm text-gray-600 mt-2">[Insert Google Form or submission link]</p>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="cta-gradient text-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">
            🚀 Ready to Duplicate and Win?
        </h2>
        <p class="text-xl mb-8 text-blue-100">
            Don't try to reinvent the wheel. Just follow the steps, share your page, and let the system do the explaining for you.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#platforms" class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-xl font-bold text-lg hover:bg-yellow-300 transition-all duration-200 transform hover:scale-105 shadow-lg">
                Get Started Now
            </a>
        </div>
        <p class="text-lg mt-6 text-blue-100">
            👉 Get started now by choosing your platform above — and let's Duplicate and Win together!
        </p>
    </div>
</section>
@endsection 