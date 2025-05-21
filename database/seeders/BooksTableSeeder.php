<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            // Fiction Classics
            ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'category' => 'Fiction', 'quantity' => 5, 'description' => 'A powerful novel about racial injustice and childhood innocence in the Deep South, seen through the eyes of young Scout Finch.'],
            ['title' => '1984', 'author' => 'George Orwell', 'category' => 'Fiction', 'quantity' => 4, 'description' => 'A dystopian classic that explores the dangers of totalitarianism and extreme political ideology in a world of constant surveillance.'],
            ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'category' => 'Fiction', 'quantity' => 3, 'description' => 'A tragic story of love, wealth, and the American Dream set in the Roaring Twenties, centered on the mysterious Jay Gatsby.'],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'category' => 'Fiction', 'quantity' => 4, 'description' => 'A witty and romantic novel about manners, marriage, and social standing in 19th-century England.'],
            ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'category' => 'Fiction', 'quantity' => 3, 'description' => 'A coming-of-age story following Holden Caulfield as he navigates teenage angst and alienation in New York City.'],

            // Modern Fiction
            ['title' => 'The Alchemist', 'author' => 'Paulo Coelho', 'category' => 'Fiction', 'quantity' => 5, 'description' => 'A novel about following your dreams and listening to your heart.'],
            ['title' => 'The Kite Runner', 'author' => 'Khaled Hosseini', 'category' => 'Fiction', 'quantity' => 4, 'description' => 'A story about the power of friendship and the redemptive power of a act of kindness.'],
            ['title' => 'The Book Thief', 'author' => 'Markus Zusak', 'category' => 'Fiction', 'quantity' => 3, 'description' => 'A story about the power of words to heal and connect people across war and time.'],
            ['title' => 'Life of Pi', 'author' => 'Yann Martel', 'category' => 'Fiction', 'quantity' => 4, 'description' => 'A story about survival, faith, and the power of storytelling.'],
            ['title' => 'The Help', 'author' => 'Kathryn Stockett', 'category' => 'Fiction', 'quantity' => 3, 'description' => 'A story about the power of friendship and the strength of women.'],

            // Science Fiction
            ['title' => 'Dune', 'author' => 'Frank Herbert', 'category' => 'Science Fiction', 'quantity' => 4, 'description' => 'A science fiction masterpiece that explores the power and politics of the desert planet Arrakis.'],
            ['title' => 'The Martian', 'author' => 'Andy Weir', 'category' => 'Science Fiction', 'quantity' => 5, 'description' => 'A thrilling science fiction novel about an astronaut stranded on Mars and his fight for survival.'],
            ['title' => 'Project Hail Mary', 'author' => 'Andy Weir', 'category' => 'Science Fiction', 'quantity' => 3, 'description' => 'A science fiction novel about a young man who wakes up on a spaceship with no memory of who he is or why he\'s there.'],
            ['title' => 'The Three-Body Problem', 'author' => 'Liu Cixin', 'category' => 'Science Fiction', 'quantity' => 4, 'description' => 'A science fiction novel about the search for extraterrestrial intelligence and the consequences of contact.'],
            ['title' => 'Neuromancer', 'author' => 'William Gibson', 'category' => 'Science Fiction', 'quantity' => 3, 'description' => 'A science fiction novel about a computer programmer who becomes entangled in a dangerous cyber-world.'],

            // Fantasy
            ['title' => 'The Name of the Wind', 'author' => 'Patrick Rothfuss', 'category' => 'Fantasy', 'quantity' => 4, 'description' => 'A fantasy novel about a young man who discovers his destiny as a powerful wizard.'],
            ['title' => 'Mistborn: The Final Empire', 'author' => 'Brandon Sanderson', 'category' => 'Fantasy', 'quantity' => 5, 'description' => 'A fantasy novel about a young woman who discovers she has the power to change the world.'],
            ['title' => 'The Way of Kings', 'author' => 'Brandon Sanderson', 'category' => 'Fantasy', 'quantity' => 3, 'description' => 'A fantasy novel about a group of young soldiers who rise to become the leaders of a new nation.'],
            ['title' => 'The Lies of Locke Lamora', 'author' => 'Scott Lynch', 'category' => 'Fantasy', 'quantity' => 4, 'description' => 'A fantasy novel about a group of thieves who use their wits and charm to outsmart their enemies.'],
            ['title' => 'The Fifth Season', 'author' => 'N.K. Jemisin', 'category' => 'Fantasy', 'quantity' => 3, 'description' => 'A fantasy novel about a world that is constantly changing and the people who live in it.'],

            // Mystery/Thriller
            ['title' => 'Gone Girl', 'author' => 'Gillian Flynn', 'category' => 'Mystery', 'quantity' => 5, 'description' => 'A psychological thriller about a woman who disappears on the day of her fifth wedding anniversary.'],
            ['title' => 'The Silent Patient', 'author' => 'Alex Michaelides', 'category' => 'Mystery', 'quantity' => 4, 'description' => 'A psychological thriller about a woman who is accused of murdering her husband.'],
            ['title' => 'The Da Vinci Code', 'author' => 'Dan Brown', 'category' => 'Mystery', 'quantity' => 3, 'description' => 'A mystery novel about a murder in the Louvre and a secret society that has been active for two thousand years.'],
            ['title' => 'The Girl with the Dragon Tattoo', 'author' => 'Stieg Larsson', 'category' => 'Mystery', 'quantity' => 4, 'description' => 'A mystery novel about a troubled hacker who helps a troubled journalist solve a fifty-year-old cold case.'],
            ['title' => 'Verity', 'author' => 'Colleen Hoover', 'category' => 'Mystery', 'quantity' => 3, 'description' => 'A psychological thriller about a woman who is forced to write a bestselling novel.'],

            // Non-Fiction
            ['title' => 'Sapiens: A Brief History of Humankind', 'author' => 'Yuval Noah Harari', 'category' => 'History', 'quantity' => 4, 'description' => 'A history book about how our species became the dominant force on Earth.'],
            ['title' => 'Atomic Habits', 'author' => 'James Clear', 'category' => 'Self-Help', 'quantity' => 5, 'description' => 'A self-help book about how small changes can lead to big improvements.'],
            ['title' => 'Thinking, Fast and Slow', 'author' => 'Daniel Kahneman', 'category' => 'Psychology', 'quantity' => 3, 'description' => 'A psychology book about the two systems of the human mind.'],
            ['title' => 'The Psychology of Money', 'author' => 'Morgan Housel', 'category' => 'Finance', 'quantity' => 4, 'description' => 'A finance book about the psychology of investing and saving.'],
            ['title' => 'Educated', 'author' => 'Tara Westover', 'category' => 'Memoir', 'quantity' => 3, 'description' => 'A memoir about a young woman who, kept out of school, leaves her survivalist family and goes on to earn a PhD from Cambridge University.'],

            // Business
            ['title' => 'Good to Great', 'author' => 'Jim Collins', 'category' => 'Business', 'quantity' => 4, 'description' => 'A business book about how companies can achieve sustained long-term growth.'],
            ['title' => 'The Lean Startup', 'author' => 'Eric Ries', 'category' => 'Business', 'quantity' => 5, 'description' => 'A business book about how to build a successful startup.'],
            ['title' => 'Zero to One', 'author' => 'Peter Thiel', 'category' => 'Business', 'quantity' => 3, 'description' => 'A business book about how to create and capture value in the modern economy.'],
            ['title' => 'The 7 Habits of Highly Effective People', 'author' => 'Stephen Covey', 'category' => 'Business', 'quantity' => 4, 'description' => 'A business book about how to achieve personal and professional effectiveness.'],
            ['title' => 'Rich Dad Poor Dad', 'author' => 'Robert Kiyosaki', 'category' => 'Business', 'quantity' => 3, 'description' => 'A business book about the importance of financial literacy and investing.'],

            // Technology
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'category' => 'Technology', 'quantity' => 4, 'description' => 'A technology book about how to write clean, maintainable, and efficient code.'],
            ['title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt', 'category' => 'Technology', 'quantity' => 5, 'description' => 'A technology book about how to write better code and be a better programmer.'],
            ['title' => 'Designing Data-Intensive Applications', 'author' => 'Martin Kleppmann', 'category' => 'Technology', 'quantity' => 3, 'description' => 'A technology book about designing scalable and efficient data systems.'],
            ['title' => 'Cracking the Coding Interview', 'author' => 'Gayle Laakmann McDowell', 'category' => 'Technology', 'quantity' => 4, 'description' => 'A technology book about preparing for technical interviews.'],
            ['title' => 'The Art of Computer Programming', 'author' => 'Donald Knuth', 'category' => 'Technology', 'quantity' => 3, 'description' => 'A technology book about the art and science of computer programming.'],

            // Philosophy
            ['title' => 'Meditations', 'author' => 'Marcus Aurelius', 'category' => 'Philosophy', 'quantity' => 4, 'description' => 'A philosophy book about the art of living well.'],
            ['title' => 'The Republic', 'author' => 'Plato', 'category' => 'Philosophy', 'quantity' => 5, 'description' => 'A philosophy book about the ideal state and the nature of justice.'],
            ['title' => 'Beyond Good and Evil', 'author' => 'Friedrich Nietzsche', 'category' => 'Philosophy', 'quantity' => 3, 'description' => 'A philosophy book about the struggle between good and evil.'],
            ['title' => 'The Art of War', 'author' => 'Sun Tzu', 'category' => 'Philosophy', 'quantity' => 4, 'description' => 'A philosophy book about the art of war and strategy.'],
            ['title' => 'The Prince', 'author' => 'Niccolò Machiavelli', 'category' => 'Philosophy', 'quantity' => 3, 'description' => 'A philosophy book about the art of politics and power.'],

            // Science
            ['title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'category' => 'Science', 'quantity' => 4, 'description' => 'A science book about the nature of the universe and the future of humanity.'],
            ['title' => 'The Selfish Gene', 'author' => 'Richard Dawkins', 'category' => 'Science', 'quantity' => 5, 'description' => 'A science book about the selfish gene and its role in evolution.'],
            ['title' => 'Cosmos', 'author' => 'Carl Sagan', 'category' => 'Science', 'quantity' => 3, 'description' => 'A science book about the nature of the universe and our place in it.'],
            ['title' => 'The Gene: An Intimate History', 'author' => 'Siddhartha Mukherjee', 'category' => 'Science', 'quantity' => 4, 'description' => 'A science book about the history of the gene and its role in human health and disease.'],
            ['title' => 'The Emperor of All Maladies', 'author' => 'Siddhartha Mukherjee', 'category' => 'Science', 'quantity' => 3, 'description' => 'A science book about cancer and its impact on human history.'],

            // Additional Fiction
            ['title' => 'The Midnight Library', 'author' => 'Matt Haig', 'category' => 'Fiction', 'quantity' => 4, 'description' => 'A fantasy novel about a woman who discovers the library of her past lives.'],
            ['title' => 'Klara and the Sun', 'author' => 'Kazuo Ishiguro', 'category' => 'Fiction', 'quantity' => 5, 'description' => 'A science fiction novel about a young girl who is a sunlamp in a futuristic world.'],
            ['title' => 'The Seven Husbands of Evelyn Hugo', 'author' => 'Taylor Jenkins Reid', 'category' => 'Fiction', 'quantity' => 3, 'description' => 'A romance novel about a woman who tells her life story to a young journalist.'],
            ['title' => 'A Man Called Ove', 'author' => 'Fredrik Backman', 'category' => 'Fiction', 'quantity' => 4, 'description' => 'A novel about a grumpy old man who finds new purpose in life.'],
            ['title' => 'The House in the Cerulean Sea', 'author' => 'TJ Klune', 'category' => 'Fiction', 'quantity' => 3, 'description' => 'A fantasy novel about a house that comes to life and falls in love with a young girl.'],

            // Additional Fantasy
            ['title' => 'The Priory of the Orange Tree', 'author' => 'Samantha Shannon', 'category' => 'Fantasy', 'quantity' => 4, 'description' => 'A fantasy novel about a young woman who discovers she has the power to change the world.'],
            ['title' => 'The Invisible Life of Addie LaRue', 'author' => 'V.E. Schwab', 'category' => 'Fantasy', 'quantity' => 5, 'description' => 'A fantasy novel about a woman who makes a deal with the devil.'],
            ['title' => 'The Thursday Murder Club', 'author' => 'Richard Osman', 'category' => 'Mystery', 'quantity' => 3, 'description' => 'A mystery novel about a group of friends who solve murders.'],

            // Additional Non-Fiction
            ['title' => 'Range: Why Generalists Triumph in a Specialized World', 'author' => 'David Epstein', 'category' => 'Psychology', 'quantity' => 4, 'description' => 'A psychology book about why generalists are better at solving complex problems than specialists.'],
            ['title' => 'Think Again: The Power of Knowing What You Don\'t Know', 'author' => 'Adam Grant', 'category' => 'Psychology', 'quantity' => 5, 'description' => 'A psychology book about the power of rethinking and learning from failure.'],
            ['title' => 'The Code Breaker', 'author' => 'Walter Isaacson', 'category' => 'Science', 'quantity' => 3, 'description' => 'A science book about the race to decode the human genome.'],
            ['title' => 'Empire of Pain', 'author' => 'Patrick Radden Keefe', 'category' => 'History', 'quantity' => 4, 'description' => 'A history book about the opioid epidemic and its impact on American society.'],
            ['title' => 'The Premonition', 'author' => 'Michael Lewis', 'category' => 'Science', 'quantity' => 3, 'description' => 'A science book about the science of predicting the future.'],

            // Additional Business
            ['title' => 'No Rules Rules', 'author' => 'Reed Hastings', 'category' => 'Business', 'quantity' => 4, 'description' => 'A business book about how to create a successful company culture.'],
            ['title' => 'Think Like a Monk', 'author' => 'Jay Shetty', 'category' => 'Self-Help', 'quantity' => 5, 'description' => 'A self-help book about how to find inner peace and happiness.'],
            ['title' => 'The Ride of a Lifetime', 'author' => 'Robert Iger', 'category' => 'Business', 'quantity' => 3, 'description' => 'A business book about the leadership lessons learned from a lifetime in the entertainment industry.'],
            ['title' => 'Can\'t Hurt Me', 'author' => 'David Goggins', 'category' => 'Self-Help', 'quantity' => 4, 'description' => 'A self-help book about overcoming adversity and achieving your goals.'],
            ['title' => 'The Psychology of Money', 'author' => 'Morgan Housel', 'category' => 'Finance', 'quantity' => 3, 'description' => 'A finance book about the psychology of investing and saving.'],

            // Additional Technology
            ['title' => 'The Code: Silicon Valley and the Remaking of America', 'author' => 'Margaret O\'Mara', 'category' => 'Technology', 'quantity' => 4, 'description' => 'A history book about the impact of Silicon Valley on American society.'],
            ['title' => 'The Innovators', 'author' => 'Walter Isaacson', 'category' => 'Technology', 'quantity' => 5, 'description' => 'A biography book about the lives and innovations of 12 influential technology pioneers.'],
            ['title' => 'The Art of Invisibility', 'author' => 'Kevin Mitnick', 'category' => 'Technology', 'quantity' => 3, 'description' => 'A technology book about how to hide from and detect others.'],
            ['title' => 'The Future of the Mind', 'author' => 'Michio Kaku', 'category' => 'Science', 'quantity' => 4, 'description' => 'A science book about the future of the human mind and the possibilities of artificial intelligence.'],
            ['title' => 'The Singularity Is Near', 'author' => 'Ray Kurzweil', 'category' => 'Technology', 'quantity' => 3, 'description' => 'A technology book about the future of technology and the singularity.'],

            // Additional Philosophy
            ['title' => 'The Daily Stoic', 'author' => 'Ryan Holiday', 'category' => 'Philosophy', 'quantity' => 4, 'description' => 'A philosophy book about the daily practices of Stoic philosophy.'],
            ['title' => 'Letters from a Stoic', 'author' => 'Seneca', 'category' => 'Philosophy', 'quantity' => 5, 'description' => 'A philosophy book about the letters of Seneca to Lucilius.'],
            ['title' => 'The Consolations of Philosophy', 'author' => 'Alain de Botton', 'category' => 'Philosophy', 'quantity' => 3, 'description' => 'A philosophy book about the consolations of philosophy.'],
            ['title' => 'The Problems of Philosophy', 'author' => 'Bertrand Russell', 'category' => 'Philosophy', 'quantity' => 4, 'description' => 'A philosophy book about the problems of philosophy.'],
            ['title' => 'The Myth of Sisyphus', 'author' => 'Albert Camus', 'category' => 'Philosophy', 'quantity' => 3, 'description' => 'A philosophy book about the myth of Sisyphus.'],

            // Additional Science
            ['title' => 'The Hidden Life of Trees', 'author' => 'Peter Wohlleben', 'category' => 'Science', 'quantity' => 4, 'description' => 'A science book about the hidden life of trees.'],
            ['title' => 'The Sixth Extinction', 'author' => 'Elizabeth Kolbert', 'category' => 'Science', 'quantity' => 5, 'description' => 'A science book about the sixth extinction event.'],
            ['title' => 'The Gene: An Intimate History', 'author' => 'Siddhartha Mukherjee', 'category' => 'Science', 'quantity' => 3, 'description' => 'A science book about the history of the gene and its role in human health and disease.'],
            ['title' => 'The Order of Time', 'author' => 'Carlo Rovelli', 'category' => 'Science', 'quantity' => 4, 'description' => 'A science book about the order of time.'],
            ['title' => 'The Hidden Reality', 'author' => 'Brian Greene', 'category' => 'Science', 'quantity' => 3, 'description' => 'A science book about the hidden reality of the universe.'],
        ];

        foreach ($books as $book) {
            DB::table('books')->insert([
                'title' => $book['title'],
                'author' => $book['author'],
                'category' => $book['category'],
                'quantity' => $book['quantity'],
                'is_available' => $book['quantity'] > 0,
                'description' => $book['description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
