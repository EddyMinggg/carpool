<x-app-layout>
    @section('Title', __('Terms of Service'))
    
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <div class="bg-secondary dark:bg-secondary-accent rounded-2xl shadow-xl p-8 md:p-12">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ __('Terms of Service') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">{{ __('Last updated: October 2025') }}</p>

            <div class="prose prose-lg max-w-none dark:text-gray-300">
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Please read these terms and conditions carefully before using Our Service.') }}
                </p>

                <!-- Acknowledgment -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Acknowledgment') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('These are the Terms of Service governing the use of this Service and the agreement that operates between You and the Company. These Terms of Service set out the rights and obligations of all users regarding the use of the Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Your access to and use of the Service is conditioned on Your acceptance of and compliance with these Terms of Service. These Terms of Service apply to all visitors, users and others who access or use the Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('By accessing or using the Service You agree to be bound by these Terms of Service. If You disagree with any part of these Terms of Service then You may not access the Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You represent that you are over the age of 18. The Company does not permit those under 18 to use the Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Your access to and use of the Service is also conditioned on Your acceptance of and compliance with the Privacy Policy of the Company. Our Privacy Policy describes Our policies and procedures on the collection, use and disclosure of Your personal information when You use the Application or the Website and tells You about Your privacy rights and how the law protects You. Please read Our Privacy Policy carefully before using Our Service.') }}
                </p>

                <!-- User Accounts -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('User Accounts') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('When You create an account with Us, You must provide Us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of Your account on Our Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You are responsible for safeguarding the password that You use to access the Service and for any activities or actions under Your password, whether Your password is with Our Service or a Third-Party Social Media Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You agree not to disclose Your password to any third party. You must notify Us immediately upon becoming aware of any breach of security or unauthorized use of Your account.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('You may not use as a username the name of another person or entity or that is not lawfully available for use, a name or trademark that is subject to any rights of another person or entity other than You without appropriate authorization, or a name that is otherwise offensive, vulgar or obscene.') }}
                </p>

                <!-- Content -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Content') }}</h2>
                
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">{{ __('Your Right to Post Content') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Our Service allows You to post Content. You are responsible for the Content that You post to the Service, including its legality, reliability, and appropriateness.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('By posting Content to the Service, You grant Us the right and license to use, modify, publicly perform, publicly display, reproduce, and distribute such Content on and through the Service. You retain any and all of Your rights to any Content You submit, post or display on or through the Service and You are responsible for protecting those rights. You agree that this license includes the right for Us to make Your Content available to other users of the Service, who may also use Your Content subject to these Terms.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('You represent and warrant that: (i) the Content is Yours (You own it) or You have the right to use it and grant Us the rights and license as provided in these Terms, and (ii) the posting of Your Content on or through the Service does not violate the privacy rights, publicity rights, copyrights, contract rights or any other rights of any person.') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">{{ __('Content Restrictions') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('The Company is not responsible for the content of the Service\'s users. You expressly understand and agree that You are solely responsible for the Content and for all activity that occurs under your account, whether done so by You or any third person using Your account.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You may not transmit any Content that is unlawful, offensive, upsetting, intended to disgust, threatening, libelous, defamatory, obscene or otherwise objectionable. Examples of such objectionable Content include, but are not limited to, the following:') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6 space-y-2">
                    <li>{{ __('Unlawful or promoting unlawful activity.') }}</li>
                    <li>{{ __('Defamatory, discriminatory, or mean-spirited content, including references or commentary about religion, race, sexual orientation, gender, national/ethnic origin, or other targeted groups.') }}</li>
                    <li>{{ __('Spam, machine – or randomly – generated, constituting unauthorized or unsolicited advertising, chain letters, any other form of unauthorized solicitation, or any form of lottery or gambling.') }}</li>
                    <li>{{ __('Containing or installing any viruses, worms, malware, trojan horses, or other content that is designed or intended to disrupt, damage, or limit the functioning of any software, hardware or telecommunications equipment or to damage or obtain unauthorized access to any data or other information of a third person.') }}</li>
                    <li>{{ __('Infringing on any proprietary rights of any party, including patent, trademark, trade secret, copyright, right of publicity or other rights.') }}</li>
                    <li>{{ __('Impersonating any person or entity including the Company and its employees or representatives.') }}</li>
                    <li>{{ __('Violating the privacy of any third person.') }}</li>
                    <li>{{ __('False information and features.') }}</li>
                </ul>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('The Company reserves the right, but not the obligation, to, in its sole discretion, determine whether or not any Content is appropriate and complies with this Terms, refuse or remove this Content. The Company further reserves the right to make formatting and edits and change the manner of any Content. The Company can also limit or revoke the use of the Service if You post such objectionable Content. As the Company cannot control all content posted by users and/or third parties on the Service, you agree to use the Service at your own risk. You understand that by using the Service You may be exposed to content that You may find offensive, indecent, incorrect or objectionable, and You agree that under no circumstances will the Company be liable in any way for any content, including any errors or omissions in any content, or any loss or damage of any kind incurred as a result of your use of any content.') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">{{ __('Content Backups') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Although regular backups of Content are performed, the Company does not guarantee there will be no loss or corruption of data.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Corrupt or invalid backup points may be caused by, without limitation, Content that is corrupted prior to being backed up or that changes during the time a backup is performed.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('The Company will provide support and attempt to troubleshoot any known or discovered issues that may affect the backups of Content. But You acknowledge that the Company has no liability related to the integrity of Content or the failure to successfully restore Content to a usable state.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('You agree to maintain a complete and accurate copy of any Content in a location independent of the Service.') }}
                </p>

                <!-- Copyright Policy -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Copyright Policy') }}</h2>
                
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">{{ __('Intellectual Property Infringement') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('We respect the intellectual property rights of others. It is Our policy to respond to any claim that Content posted on the Service infringes a copyright or other intellectual property infringement of any person.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('If You are a copyright owner, or authorized on behalf of one, and You believe that the copyrighted work has been copied in a way that constitutes copyright infringement that is taking place through the Service, You must submit Your notice in writing to the attention of our copyright agent via email at danielwu@snowpins.com and include in Your notice a detailed description of the alleged infringement.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('You may be held accountable for damages (including costs and attorneys\' fees) for misrepresenting that any Content is infringing Your copyright.') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">{{ __('DMCA Notice and DMCA Procedure for Copyright Infringement Claims') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('You may submit a notification pursuant to the Digital Millennium Copyright Act (DMCA) by providing our Copyright Agent with the following information in writing (see 17 U.S.C 512(c)(3) for further detail):') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6 space-y-2">
                    <li>{{ __('An electronic or physical signature of the person authorized to act on behalf of the owner of the copyright\'s interest.') }}</li>
                    <li>{{ __('A description of the copyrighted work that You claim has been infringed, including the URL (i.e., web page address) of the location where the copyrighted work exists or a copy of the copyrighted work.') }}</li>
                    <li>{{ __('Identification of the URL or other specific location on the Service where the material that You claim is infringing is located.') }}</li>
                    <li>{{ __('Your address, telephone number, and email address.') }}</li>
                    <li>{{ __('A statement by You that You have a good faith belief that the disputed use is not authorized by the copyright owner, its agent, or the law.') }}</li>
                    <li>{{ __('A statement by You, made under penalty of perjury, that the above information in Your notice is accurate and that You are the copyright owner or authorized to act on the copyright owner\'s behalf.') }}</li>
                </ul>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('You can contact our copyright agent via email at danielwu@snowpins.com. Upon receipt of a notification, the Company will take whatever action, in its sole discretion, it deems appropriate, including removal of the challenged content from the Service.') }}
                </p>

                <!-- Intellectual Property -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Intellectual Property') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('The Service and its original content (excluding Content provided by You or other users), features and functionality are and will remain the exclusive property of the Company and its licensors.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('The Service is protected by copyright, trademark, and other laws of both the Country and foreign countries. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of the Company.') }}
                </p>

                <!-- Your Feedback to Us -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Your Feedback to Us') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('You assign all rights, title and interest in any Feedback You provide the Company. If for any reason such assignment is ineffective, You agree to grant the Company a non-exclusive, perpetual, irrevocable, royalty free, worldwide right and license to use, reproduce, disclose, sub-license, distribute, modify and exploit such Feedback without restriction.') }}
                </p>

                <!-- Links to Other Websites -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Links to Other Websites') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Our Service may contain links to third-party web sites or services that are not owned or controlled by the Company.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('The Company has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web sites or services. You further acknowledge and agree that the Company shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with the use of or reliance on any such content, goods or services available on or through any such web sites or services.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('We strongly advise You to read the terms and conditions and privacy policies of any third-party web sites or services that You visit.') }}
                </p>

                <!-- Termination -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Termination') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('We may terminate or suspend Your Account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if You breach these Terms of Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Upon termination, Your right to use the Service will cease immediately. If You wish to terminate Your Account, You may simply discontinue using the Service.') }}
                </p>

                <!-- Limitation of Liability -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Limitation of Liability') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Notwithstanding any damages that You might incur, the entire liability of the Company and any of its suppliers under any provision of this Terms and Your exclusive remedy for all of the foregoing shall be limited to the amount actually paid by You through the Service or 100 USD if You haven\'t purchased anything through the Service.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('To the maximum extent permitted by applicable law, in no event shall the Company or its suppliers be liable for any special, incidental, indirect, or consequential damages whatsoever (including, but not limited to, damages for loss of profits, loss of data or other information, for business interruption, for personal injury, loss of privacy arising out of or in any way related to the use of or inability to use the Service, third-party software and/or third-party hardware used with the Service, or otherwise in connection with any provision of this Terms), even if the Company or any supplier has been advised of the possibility of such damages and even if the remedy fails of its essential purpose.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Some states do not allow the exclusion of implied warranties or limitation of liability for incidental or consequential damages, which means that some of the above limitations may not apply. In these states, each party\'s liability will be limited to the greatest extent permitted by law.') }}
                </p>

                <!-- "AS IS" and "AS AVAILABLE" Disclaimer -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('"AS IS" and "AS AVAILABLE" Disclaimer') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('The Service is provided to You "AS IS" and "AS AVAILABLE" and with all faults and defects without warranty of any kind. To the maximum extent permitted under applicable law, the Company, on its own behalf and on behalf of its Affiliates and its and their respective licensors and service providers, expressly disclaims all warranties, whether express, implied, statutory or otherwise, with respect to the Service, including all implied warranties of merchantability, fitness for a particular purpose, title and non-infringement, and warranties that may arise out of course of dealing, course of performance, usage or trade practice. Without limitation to the foregoing, the Company provides no warranty or undertaking, and makes no representation of any kind that the Service will meet Your requirements, achieve any intended results, be compatible or work with any other software, applications, systems or services, operate without interruption, meet any performance or reliability standards or be error free or that any errors or defects can or will be corrected.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('Without limiting the foregoing, neither the Company nor any of the company\'s provider makes any representation or warranty of any kind, express or implied: (i) as to the operation or availability of the Service, or the information, content, and materials or products included thereon; (ii) that the Service will be uninterrupted or error-free; (iii) as to the accuracy, reliability, or currency of any information or content provided through the Service; or (iv) that the Service, its servers, the content, or e-mails sent from or on behalf of the Company are free of viruses, scripts, trojan horses, worms, malware, timebombs or other harmful components.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Some jurisdictions do not allow the exclusion of certain types of warranties or limitations on applicable statutory rights of a consumer, so some or all of the above exclusions and limitations may not apply to You. But in such a case the exclusions and limitations set forth in this section shall be applied to the greatest extent enforceable under applicable law.') }}
                </p>

                <!-- Governing Law -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Governing Law') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('The laws of Hong Kong SAR, excluding its conflicts of law rules, shall govern this Terms and Your use of the Service. Your use of the Application may also be subject to other local, state, national, or international laws.') }}
                </p>

                <!-- Disputes Resolution -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Disputes Resolution') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('If You have any concern or dispute about the Service, You agree to first try to resolve the dispute informally by contacting the Company.') }}
                </p>

                <!-- Severability and Waiver -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Severability and Waiver') }}</h2>
                
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">{{ __('Severability') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('If any provision of these Terms is held to be unenforceable or invalid, such provision will be changed and interpreted to accomplish the objectives of such provision to the greatest extent possible under applicable law and the remaining provisions will continue in full force and effect.') }}
                </p>

                <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-6 mb-3">{{ __('Waiver') }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('Except as provided herein, the failure to exercise a right or to require performance of an obligation under these Terms shall not effect a party\'s ability to exercise such right or require such performance at any time thereafter nor shall the waiver of a breach constitute a waiver of any subsequent breach.') }}
                </p>

                <!-- Changes to These Terms of Service -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Changes to These Terms of Service') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('We reserve the right, at Our sole discretion, to modify or replace these Terms at any time. If a revision is material We will make reasonable efforts to provide at least 30 days\' notice prior to any new terms taking effect. What constitutes a material change will be determined at Our sole discretion.') }}
                </p>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    {{ __('By continuing to access or use Our Service after those revisions become effective, You agree to be bound by the revised terms. If You do not agree to the new terms, in whole or in part, please stop using the website and the Service.') }}
                </p>

                <!-- Contact Us -->
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">{{ __('Contact Us') }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    {{ __('If you have any questions about these Terms of Service, You can contact us:') }}
                </p>
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300 mb-6">
                    <li>{{ __('By visiting the Contact page on our website') }}</li>
                    <li>{{ __('By sending us an email: danielwu@snowpins.com') }}</li>
                </ul>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('about.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold">
                    ← {{ __('Back to About Us') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
