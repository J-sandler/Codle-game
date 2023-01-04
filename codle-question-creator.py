from multiprocessing.connection import answer_challenge
from operator import index
import random
from readline import write_history_file
from tracemalloc import start
from typing import final

question_data = [] # [[answer], [starting_array], [ending_array], [function_information]]
starting_array = [] # the first array created by starting_array_creator()
working_array = [] # intermitent array whereon operations are preformed to produce the final array
rand_order = [] # the order the functions are called in to produce the final array
final_array = [] # the final array, shown to the player in the form of an answer that they must produce
functions_permutations = [
    ['index_modulus_function', 'sum_function', 'n_modulus_function'],
    ['index_modulus_function', 'n_modulus_function', 'sum_function'],
    ['sum_function', 'n_modulus_function', 'index_modulus_function'],
    ['sum_function', 'index_modulus_function', 'n_modulus_function'],
    ['n_modulus_function', 'sum_function', 'index_modulus_function'],
    ['n_modulus_function', 'index_modulus_function', 'sum_function']]

permutation_count = 0 # number of permutations of functions that produce target answer (should be equal to 1)

Questions = open('Codle_Questions.txt', 'a')

def starting_array_creator():
  global starting_array, working_array, rand_range_lower, rand_range_upper, rand_step

  working_array = []

  rand_step = random.randint(1, 5) # the random step size used in the loop
  rand_range_upper = random.randint(30, 100)
  rand_range_lower = random.randint(1, 10)

  for i in range(rand_range_lower, rand_range_upper, rand_step):
    working_array.append(i)
  
  working_array = working_array[0: 6]
  starting_array = working_array[0: 6]


def index_modulus_function():
  global working_array, rand_modulus_index, starting_array
  
  new_array = []
  rand_modulus_index = random.randint(3, 5)
  for n in working_array:
    if (working_array.index(n) % rand_modulus_index):
      new_array.append(n)
  
  if len(new_array) < 3:
    index_modulus_function()

  working_array = new_array


def sum_function():
  global working_array, rand_sum

  new_array = []
  rand_sum = random.randint(2, 20)

  for n in working_array:
    new_array.append(n+rand_sum)
  
  if len(new_array) < 3:
    sum_function()
  
  working_array = new_array

def n_modulus_function():
  global working_array, rand_modulus

  new_array = []
  rand_modulus = random.randint(2, 4)
  for n in working_array:
    if (n % rand_modulus):
      new_array.append(n)

  if len(new_array) < 3:
    n_modulus_function()

  working_array = new_array


def final_array_creator(): 
  # Not shown to player, merely used to determine a random order of array functions to-
  # consequently produce a final array to be shown to the player

  global rand_order, final_array, working_array, functions_permutations

  rand_index = random.randint(0, 5)
  rand_order = functions_permutations[rand_index]

  starting_array_creator()

  for function in rand_order:
    if (function == 'index_modulus_function'):
      index_modulus_function()

    elif (function == 'sum_function'):
      sum_function()

    else:
      n_modulus_function()

  final_array = working_array
  return()


def function_information_compiler_python():
  global starting_array, final_array, rand_range_lower, rand_range_upper, rand_step, rand_order, rand_modulus, rand_modulus_index, rand_sum, rand_order, answer, functions_permutations, question_data

  displayed_order = functions_permutations[random.randint(0, 5)]
  answer = []
  function_information = []

  for function in displayed_order:
    if (function == 'index_modulus_function'):
      function_information.append(['index_modulus_function', rand_modulus_index])
    elif (function == 'sum_function'):
      function_information.append(['sum_function', rand_sum])
    else:
      function_information.append(['modulus_function', rand_modulus])

  for function in displayed_order:
    answer.append(f'function_{rand_order.index(function)+1}')

  question = []
  question.append(answer)
  question.append(starting_array)
  question.append(final_array)
  question.append(function_information)
  question_data.append(question)
  return()


def permutation_checker():
  global starting_array, final_array, answer, rand_modulus, rand_modulus_index, rand_sum, functions_permutations, permutation_count

  permutation_count = 0
  working_answer = starting_array
  for permutation in functions_permutations:
    for function in permutation:

      if (function[0] == 'i'):
        new_array = []
        for n in working_answer:
          if (working_answer.index(n) % rand_modulus_index):
            new_array.append(n)
        
      elif (function[0] == 's'):
        new_array = []
        for n in working_answer:
          new_array.append(n+rand_sum)
      
      else:
        new_array = []
        for n in working_answer:
          if (n % rand_modulus):
            new_array.append(n)
      
      working_answer = new_array
    
    if (working_answer == final_array):
      permutation_count += 1
    
    working_answer = starting_array


def functional_question_creator(language):
  global permutation_count

  if (not permutation_count) or (permutation_count > 1):
    final_array_creator()
    permutation_checker()
    functional_question_creator(language)
  
  if permutation_count == 1:
    if language == 'python':
      function_information_compiler_python()
    
    return()


functional_question_creator('python')
Questions.write(f'Q: {question_data[-1]} \n')
