<x-app-layout>
    @section('Title', __('Privacy Policy'))
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 md:p-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Privacy Policy') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">{{ __('Last updated: October 2025') }}</p>

            <div class="prose prose-lg max-w-none dark:text-gray-300">
                <!-- Who We Are -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Who We Are') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('At snowpins.com, we are committed to maintaining the trust and confidence of all visitors to our web site. In particular, we want you to know that snowpins.com is not in the business of selling, renting or trading email lists with other companies and businesses for marketing purposes.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('In this Privacy Policy, we\'ve provided detailed information on when and why we collect personal information, how we use it, the limited conditions under which we may disclose it to others, and how we keep it secure.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('We take your privacy seriously and take measures to provide all visitors and users of snowpins.com with a safe and secure environment.') }}
                </p>

                <!-- Information We Collect -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Information We Collect') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('We collect information you provide directly to us when you create an account, book a trip, or contact our customer service, including:') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6">
                    <li>{{ __('Personal identification information (name, email address, phone number)') }}</li>
                    <li>{{ __('Payment information') }}</li>
                    <li>{{ __('Trip booking details and preferences') }}</li>
                    <li>{{ __('Communication preferences') }}</li>
                </ul>

                <!-- Cookies -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Cookies') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('snowpins.com may set and access snowpins.com cookies on your computer. Cookies are used to provide our system with the basic information to provide the services you are requesting. Cookies can be cleared at any time from your internet browser settings.') }}
                </p>

                <!-- Google Analytics -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Google Analytics') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('When someone visits snowpins.com we use a third party service, Google Analytics, to collect standard internet log information and details of visitor behaviour patterns. We do this to track things such as the number of visitors to the various parts of the site and interactions with the site. This information is processed in a way which does not identify anyone. We do not make, and do not allow Google to make, any attempt to find out the identities of visitors to our website.') }}
                </p>

                <!-- How We Use Your Information -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('How We Use Your Information') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ __('We use the information we collect to:') }}</p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6">
                    <li>{{ __('Provide, maintain, and improve our services') }}</li>
                    <li>{{ __('Process your bookings and payments') }}</li>
                    <li>{{ __('Send you technical notices, updates, and support messages') }}</li>
                    <li>{{ __('Respond to your comments, questions, and customer service requests') }}</li>
                    <li>{{ __('Communicate with you about services, offers, and events') }}</li>
                </ul>

                <!-- Website Comments -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Website Comments') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('When someone visits snowpins.com, there may be an ability to submit comments on particular articles or pages. When comments are submitted, you are entitled to use aliases or information that completely hides your identity. When a comment is submitted, the relevant details (name, email, website) that you provide are stored. These details are stored so that we can display your comment back to you, and to anyone viewing the comment sections on the site. We do not verify information entered nor do we require verification.') }}
                </p>

                <!-- WhatsApp Communication -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('WhatsApp Communication') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('We only send WhatsApp messages to customers who have:') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6">
                    <li>{{ __('Provided their mobile phone number to us') }}</li>
                    <li>{{ __('Given explicit opt-in consent to receive messages') }}</li>
                    <li>{{ __('You can opt-out anytime by sending "STOP" to our WhatsApp Business number') }}</li>
                </ul>

                <!-- Third Parties -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Third Parties') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('There may be some circumstances where your IP address, geographic location, and other browser related details may be shared with third party companies. We may share your above mentioned data with following third party companies from time to time.') }}
                </p>

                <!-- Data Protection -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Data Protection') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('We implement appropriate security measures to protect your personal information, including:') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6">
                    <li>{{ __('Encryption of sensitive data') }}</li>
                    <li>{{ __('Regular security assessments') }}</li>
                    <li>{{ __('Access controls and authentication') }}</li>
                    <li>{{ __('Compliance with Personal Data (Privacy) Ordinance') }}</li>
                </ul>

                <!-- Access to Your Personal Information -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Access to Your Personal Information') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ __('You have the right to:') }}</p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6">
                    <li>{{ __('Access your personal data') }}</li>
                    <li>{{ __('Correct inaccurate data') }}</li>
                    <li>{{ __('Request deletion of your data') }}</li>
                    <li>{{ __('Opt-out of marketing communications') }}</li>
                    <li>{{ __('Lodge a complaint with relevant authorities') }}</li>
                </ul>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Email your request to our data protection officer Snow at snowpinshk@gmail.com and we will work with you to remove any of your personal data we may have.') }}
                </p>

                <!-- Changes to Our Privacy Policy -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Changes to Our Privacy Policy') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('We may make changes to our Privacy Policy in the future, however, the most current version of the policy will govern our processing of your personal data and will always be available to you.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('If we make a change to this policy that, in our sole discretion, is material, we will notify you by an update or email, where possible. By continuing to access or use our services, you agree to be bound to the terms of our Privacy Policy.') }}
                </p>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('about.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold">
                    ← {{ __('Back to About Us') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
