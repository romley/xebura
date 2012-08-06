<?php
//random quote generator - JLR on 04-05-2010
$random_text = array("Odontophobia is the fear of teeth.",
					"It took Leo Tolstoy six years to write \"War & Peace\"'. ",
					"American car horns beep in the tone of F.",
					"Dueling is legal in Paraguay as long as both parties are registered blood donors.",
					"Children grow faster in the springtime.",
					"Minus 40 degrees Celsius is exactly the same as minus 40 degrees Fahrenheit.",
					"Every human spent about half an hour as a single cell.",
					"Texas is the only state that allows its residents to vote from space.",
					"Infants spend more time dreaming than adults do.",
					"On average, we spend about 6 months of our lives waiting at red traffic lights.",
					"Patience can conquer destiny.",
					"One minute of patience, ten years of peace.",
					"Somewhere, something incredible is waiting to be known.",
					"Life is always a matter of waiting for the right moment to act.",
					"The world is full of magical things patiently waiting for our wits to grow sharper.",
                    "The first coast to coast US airplane flight occurred in 1911 and took 49 days.",
					"If you only do what you know you can do you never do very much.",
					"Don't wait. The time will never be just right.",
					"Action conquers fear.",
					"Believe and act as if it were impossible to fail.",
					"Action is the real measure of intelligence.",
					"Be the change you want to see in the world.",
					"Victory is sweetest when you've known defeat.",
					"To succeed in life, you need two things: ignorance and confidence.",
					"Moderation is a fatal thing. Nothing succeeds like excess.",
					"Act on the advice you give to others.",
					"Reality is merely an illusion, although a very persistent one.",
					"The art and science of asking questions is the source of all knowledge.",
					"Music is what feelings sound like.",
					"Try and fail, but don't fail to try.",
					"Consistency is the last refuge of the unimaginative.",
					"I shut my eyes in order to see.",
					"Any sufficiently advanced technology is indistinguishable from magic.",
					"Sometimes it is the quiet observer who see the most.",
					"Do not bite at the bait of pleasure till you know there is no hook beneath it.",
					"Forever is composed of nows.",
					"The truth is more important than the facts.");

srand(time());
$sizeof = count($random_text);
$random = (rand()%$sizeof);
$random_quote = $random_text[$random];

$smarty->assign("random_quote",$random_quote);
//endrandomrquote

?>